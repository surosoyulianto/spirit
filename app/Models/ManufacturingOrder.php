<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class ManufacturingOrder extends Model
{
    // Jika semua kolom di database bisa diisi langsung
    protected $fillable = [
        'mo_number',
        'product_id',
        'quantity',
        'status',
        'scheduled_date',
        'notes',
        'completed_date',
    ];

    // (Opsional) Jika kamu ingin pakai enum status dan tampilkan badge
    public function getStatusBadgeAttribute()
    {
        return match ($this->status) {
            'draft' => 'bg-yellow-100 text-yellow-700',
            'confirmed' => 'bg-blue-100 text-blue-700',
            'in_progress' => 'bg-indigo-100 text-indigo-700',
            'done' => 'bg-green-100 text-green-700',
            'cancelled' => 'bg-red-100 text-red-700',
            default => 'bg-gray-100 text-gray-700',
        };
    }

    /**
     * Relationship: ManufacturingOrder belongs to Product (finished goods)
     */
    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    /**
     * Relationship: ManufacturingOrder has many materials (raw materials) with pivot quantity
     */
    public function materials(): BelongsToMany
    {
        return $this->belongsToMany(Product::class, 'manufacturing_order_materials')
                    ->withPivot('quantity')
                    ->withTimestamps();
    }

    /**
     * Relationship: ManufacturingOrder has many Inventory movements
     */
    public function inventories()
    {
        return $this->hasMany(Inventory::class, 'reference_id')->where('reference_type', 'manufacturing_order');
    }

    /**
     * Check if MO can be started
     */
    public function canStart(): bool
    {
        return in_array($this->status, ['draft', 'confirmed']);
    }

    /**
     * Check if MO can be completed
     */
    public function canComplete(): bool
    {
        return $this->status === 'in_progress';
    }

    /**
     * Check if MO can be cancelled
     */
    public function canCancel(): bool
    {
        return !in_array($this->status, ['done', 'cancelled']);
    }

    /**
     * Get available status transitions
     */
    public function getAvailableTransitions(): array
    {
        return match ($this->status) {
            'draft' => ['confirmed', 'cancelled'],
            'confirmed' => ['in_progress', 'cancelled'],
            'in_progress' => ['done', 'cancelled'],
            default => [],
        };
    }

    /**
     * Transition to new status with inventory actions
     */
    public function transitionTo($newStatus)
    {
        // Validate transition
        if (!in_array($newStatus, $this->getAvailableTransitions())) {
            return false;
        }

        // Handle inventory actions based on status change
        if ($newStatus === 'in_progress') {
            // Starting production - deduct raw materials from inventory
            foreach ($this->materials as $material) {
                $pivotQuantity = $material->pivot->quantity * $this->quantity;
                
                // Deduct raw material stock
                $material->decrement('stock', $pivotQuantity);
                
                // Record inventory movement
                Inventory::recordMovement(
                    $material->id,
                    $pivotQuantity,
                    'out',
                    "Production started: {$this->mo_number} (Raw material)",
                    'manufacturing_order',
                    $this->id
                );
            }
        }

        if ($newStatus === 'done') {
            // Production complete - add finished goods to inventory
            $this->product->increment('stock', $this->quantity);

            // Record inventory movement
            Inventory::recordMovement(
                $this->product_id,
                $this->quantity,
                'in',
                "Production completed: {$this->mo_number}",
                'manufacturing_order',
                $this->id
            );
        }

        if ($newStatus === 'cancelled' && $this->status === 'in_progress') {
            // If cancelled during production, return raw materials to inventory
            foreach ($this->materials as $material) {
                $pivotQuantity = $material->pivot->quantity * $this->quantity;
                
                // Return raw material stock
                $material->increment('stock', $pivotQuantity);
                
                // Record inventory movement
                Inventory::recordMovement(
                    $material->id,
                    $pivotQuantity,
                    'in',
                    "Production cancelled (materials returned): {$this->mo_number}",
                    'manufacturing_order',
                    $this->id
                );
            }
        }

        $this->update([
            'status' => $newStatus,
            'completed_date' => $newStatus === 'done' ? now()->toDateString() : null,
        ]);

        return true;
    }

    /**
     * Calculate total material cost
     */
    public function getTotalMaterialCostAttribute(): float
    {
        $total = 0;
        foreach ($this->materials as $material) {
            $total += $material->price * ($material->pivot->quantity * $this->quantity);
        }
        return $total;
    }
}
