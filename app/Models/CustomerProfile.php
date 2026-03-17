<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CustomerProfile extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id', 'phone', 'address', 'gender', 'birth_date',
        'member_code', 'total_points', 'total_washes', 'lifetime_points',
    ];

    protected function casts(): array
    {
        return [
            'birth_date' => 'date',
        ];
    }

    // ── Relationships ──
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function vehicles()
    {
        return $this->hasMany(Vehicle::class);
    }

    public function transactions()
    {
        return $this->hasMany(TransactionHead::class);
    }

    public function pointHistories()
    {
        return $this->hasMany(PointHistory::class);
    }

    public function rewardClaims()
    {
        return $this->hasMany(RewardClaim::class);
    }

    // ── Business Logic ──
    public static function generateMemberCode(): string
    {
        $prefix = 'WH';
        $date = now()->format('ym');
        $last = self::where('member_code', 'like', "{$prefix}{$date}%")
            ->orderBy('member_code', 'desc')
            ->first();

        if ($last) {
            $lastNumber = (int) substr($last->member_code, -4);
            $newNumber = $lastNumber + 1;
        } else {
            $newNumber = 1;
        }

        return $prefix . $date . str_pad($newNumber, 4, '0', STR_PAD_LEFT);
    }

    public function canClaimReward(): bool
    {
        return $this->total_washes >= 10 && ($this->total_washes % 10 === 0 || $this->hasUnclaimedReward());
    }

    public function getAvailableFreeWashes(): int
    {
        return floor($this->total_washes / 10) - $this->rewardClaims()->whereIn('status', ['claimed', 'used'])->count();
    }

    public function hasUnclaimedReward(): bool
    {
        return $this->getAvailableFreeWashes() > 0;
    }

    public function addPoints(int $points, ?int $transactionId = null, string $description = ''): void
    {
        $this->increment('total_points', $points);
        $this->increment('lifetime_points', $points);

        $this->pointHistories()->create([
            'transaction_head_id' => $transactionId,
            'type' => 'earned',
            'points' => $points,
            'balance_after' => $this->total_points,
            'description' => $description ?: "Earned {$points} points",
        ]);
    }

    public function redeemPoints(int $points, ?int $transactionId = null, string $description = ''): bool
    {
        if ($this->total_points < $points) {
            return false;
        }

        $this->decrement('total_points', $points);

        $this->pointHistories()->create([
            'transaction_head_id' => $transactionId,
            'type' => 'redeemed',
            'points' => $points,
            'balance_after' => $this->total_points,
            'description' => $description ?: "Redeemed {$points} points",
        ]);

        return true;
    }
}