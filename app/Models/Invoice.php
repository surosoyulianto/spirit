<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    use HasFactory;

    protected $fillable = [
        'invoice_number',
        'customer_id',
        'invoice_date',
        'total',
        'status',
    ];

    // Relasi ke Customer
    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    // Relasi ke Invoice Items
    public function items()
    {
        return $this->hasMany(InvoiceItem::class);
    }

    // Scope untuk filter status, misalnya: Invoice::status('draft')->get();
    public function scopeStatus($query, $status)
    {
        return $query->where('status', $status);
    }
}
