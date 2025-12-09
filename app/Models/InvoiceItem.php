<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InvoiceItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'invoice_id',
        'product_id',
        'description',
        'quantity',
        'unit_price',
        'subtotal',
    ];

    public function invoice()
    {
        return $this->belongsTo(Invoice::class);
    }

    // Optional accessor: subtotal
    public function getSubtotalAttribute()
    {
        return $this->quantity * $this->unit_price;
    }
}
