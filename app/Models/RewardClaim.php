<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RewardClaim extends Model
{
    use HasFactory;

    protected $fillable = [
        'customer_profile_id', 'transaction_head_id', 'reward_type',
        'washes_required', 'washes_at_claim', 'status',
        'claimed_at', 'used_at', 'expired_at', 'notes',
    ];

    protected function casts(): array
    {
        return [
            'claimed_at' => 'datetime',
            'used_at' => 'datetime',
            'expired_at' => 'datetime',
        ];
    }

    public function customerProfile()
    {
        return $this->belongsTo(CustomerProfile::class);
    }

    public function transactionHead()
    {
        return $this->belongsTo(TransactionHead::class);
    }
}