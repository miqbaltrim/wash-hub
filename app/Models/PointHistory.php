<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PointHistory extends Model
{
    use HasFactory;

    protected $fillable = [
        'customer_profile_id', 'transaction_head_id',
        'type', 'points', 'balance_after', 'description',
    ];

    public function customerProfile()
    {
        return $this->belongsTo(CustomerProfile::class);
    }

    public function transactionHead()
    {
        return $this->belongsTo(TransactionHead::class);
    }
}