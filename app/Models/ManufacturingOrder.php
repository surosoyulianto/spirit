<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ManufacturingOrder extends Model
{
    // Jika semua kolom di database bisa diisi langsung
    protected $fillable = [
        'id_order',
        'product',
        'quantity',
        'status',
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
}
