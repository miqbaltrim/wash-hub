<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TransactionDetail extends Model
{
    use HasFactory;

    protected $fillable = [
        'transaction_head_id', 'service_id', 'service_name',
        'service_category', 'unit_price', 'qty', 'discount', 'subtotal', 'notes',
    ];

    protected function casts(): array
    {
        return [
            'unit_price' => 'decimal:2',
            'discount' => 'decimal:2',
            'subtotal' => 'decimal:2',
        ];
    }

    public function transactionHead()
    {
        return $this->belongsTo(TransactionHead::class);
    }

    public function service()
    {
        return $this->belongsTo(Service::class);
    }

    // Auto-calculate subtotal
    protected static function booted(): void
    {
        static::saving(function ($detail) {
            $detail->subtotal = ($detail->unit_price * $detail->qty) - $detail->discount;
        });
    }
}