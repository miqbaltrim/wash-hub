<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Vehicle extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'customer_profile_id', 'plate_number', 'vehicle_type',
        'brand', 'model', 'color', 'year', 'notes',
    ];

    public function customerProfile()
    {
        return $this->belongsTo(CustomerProfile::class);
    }

    public function transactions()
    {
        return $this->hasMany(TransactionHead::class);
    }

    public function getFullNameAttribute(): string
    {
        return trim("{$this->brand} {$this->model} ({$this->plate_number})");
    }
}