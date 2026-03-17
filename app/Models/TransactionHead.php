<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TransactionHead extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'invoice_number', 'customer_profile_id', 'vehicle_id', 'cashier_id',
        'plate_number', 'vehicle_type', 'transaction_date',
        'subtotal', 'discount_amount', 'discount_percent', 'tax_amount', 'grand_total',
        'payment_method', 'payment_status', 'payment_amount', 'change_amount',
        'points_earned', 'points_redeemed', 'is_reward_claim',
        'wash_status', 'notes',
    ];

    protected function casts(): array
    {
        return [
            'transaction_date' => 'date',
            'subtotal' => 'decimal:2',
            'discount_amount' => 'decimal:2',
            'discount_percent' => 'decimal:2',
            'tax_amount' => 'decimal:2',
            'grand_total' => 'decimal:2',
            'payment_amount' => 'decimal:2',
            'change_amount' => 'decimal:2',
            'is_reward_claim' => 'boolean',
        ];
    }

    // ── Relationships ──
    public function customerProfile()
    {
        return $this->belongsTo(CustomerProfile::class);
    }

    public function vehicle()
    {
        return $this->belongsTo(Vehicle::class);
    }

    public function cashier()
    {
        return $this->belongsTo(User::class, 'cashier_id');
    }

    public function details()
    {
        return $this->hasMany(TransactionDetail::class);
    }

    public function pointHistories()
    {
        return $this->hasMany(PointHistory::class);
    }

    public function rewardClaim()
    {
        return $this->hasOne(RewardClaim::class);
    }

    // ── Invoice Generator ──
    public static function generateInvoiceNumber(): string
    {
        $prefix = 'INV';
        $date = now()->format('Ymd');
        $last = self::where('invoice_number', 'like', "{$prefix}-{$date}-%")
            ->orderBy('invoice_number', 'desc')
            ->first();

        if ($last) {
            $lastNumber = (int) substr($last->invoice_number, -4);
            $newNumber = $lastNumber + 1;
        } else {
            $newNumber = 1;
        }

        return "{$prefix}-{$date}-" . str_pad($newNumber, 4, '0', STR_PAD_LEFT);
    }

    // ── Helpers ──
    public function getFormattedGrandTotalAttribute(): string
    {
        return 'Rp ' . number_format($this->grand_total, 0, ',', '.');
    }

    public function getStatusBadgeAttribute(): string
    {
        return match ($this->wash_status) {
            'waiting' => '<span class="badge bg-warning">Menunggu</span>',
            'in_progress' => '<span class="badge bg-info">Sedang Dicuci</span>',
            'done' => '<span class="badge bg-success">Selesai</span>',
            'picked_up' => '<span class="badge bg-secondary">Diambil</span>',
            default => '<span class="badge bg-dark">-</span>',
        };
    }

    public function calculateTotals(): void
    {
        $subtotal = $this->details()->sum('subtotal');
        $this->subtotal = $subtotal;

        if ($this->discount_percent > 0) {
            $this->discount_amount = $subtotal * ($this->discount_percent / 100);
        }

        $afterDiscount = $subtotal - $this->discount_amount;
        $this->tax_amount = $afterDiscount * 0; // Set tax rate as needed
        $this->grand_total = $afterDiscount + $this->tax_amount;
        $this->save();
    }
}