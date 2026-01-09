<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Inventory extends Model
{
    protected $fillable = [
        'product_id',
        'quantity',
        'type',
        'reason',
        'date',
        'reference_type', // manufacturing_order, invoice, manual
        'reference_id',   // ID dari MO atau Invoice
    ];

    /**
     * Relationship: Inventory belongs to Product
     */
    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    /**
     * Scope: Get only 'in' movements
     */
    public function scopeIn($query)
    {
        return $query->where('type', 'in');
    }

    /**
     * Scope: Get only 'out' movements
     */
    public function scopeOut($query)
    {
        return $query->where('type', 'out');
    }

    /**
     * Scope: Get only 'adjustment' movements
     */
    public function scopeAdjustment($query)
    {
        return $query->where('type', 'adjustment');
    }

    /**
     * Get type badge color
     */
    public function getTypeBadgeAttribute()
    {
        return match ($this->type) {
            'in' => 'bg-green-100 text-green-700',
            'out' => 'bg-red-100 text-red-700',
            'adjustment' => 'bg-blue-100 text-blue-700',
            default => 'bg-gray-100 text-gray-700',
        };
    }

    /**
     * Create automatic inventory movement when stock changes
     */
    public static function recordMovement($productId, $quantity, $type, $reason, $referenceType = null, $referenceId = null)
    {
        return static::create([
            'product_id' => $productId,
            'quantity' => $quantity,
            'type' => $type,
            'reason' => $reason,
            'date' => now()->toDateString(),
            'reference_type' => $referenceType,
            'reference_id' => $referenceId,
        ]);
    }
}
