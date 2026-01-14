<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'price',
        'stock',
        'sku',
        'category',
        'image',
        'is_active',
        'min_stock_level', // Level minimum untuk alert
        'is_raw_material', // Apakah ini bahan baku
    ];

    /**
     * Relationship: Product has many InvoiceItems (sold products)
     */
    public function invoiceItems()
    {
        return $this->hasMany(InvoiceItem::class);
    }

    /**
     * Relationship: Product has many ManufacturingOrders (finished goods produced)
     */
    public function manufacturingOrders()
    {
        return $this->hasMany(ManufacturingOrder::class, 'product_id');
    }

    /**
     * Relationship: Product belongs to many ManufacturingOrders as a raw material
     */
    public function manufacturingMaterials(): BelongsToMany
    {
        return $this->belongsToMany(ManufacturingOrder::class, 'manufacturing_order_materials')
                    ->withPivot('quantity')
                    ->withTimestamps();
    }

    /**
     * Relationship: Product has many Inventory movements
     */
    public function inventories()
    {
        return $this->hasMany(Inventory::class);
    }

    /**
     * Get current stock balance
     */
    public function getStockBalanceAttribute()
    {
        $in = $this->inventories()->where('type', 'in')->sum('quantity') ?? 0;
        $out = $this->inventories()->where('type', 'out')->sum('quantity') ?? 0;
        return $in - $out;
    }

    /**
     * Check if stock is low
     */
    public function isLowStock(): bool
    {
        return $this->stock <= ($this->min_stock_level ?? 10);
    }

    /**
     * Get stock status badge
     */
    public function getStockStatusAttribute()
    {
        if ($this->stock <= 0) {
            return ['label' => 'Out of Stock', 'color' => 'red'];
        } elseif ($this->isLowStock()) {
            return ['label' => 'Low Stock', 'color' => 'yellow'];
        }
        return ['label' => 'In Stock', 'color' => 'green'];
    }
}
