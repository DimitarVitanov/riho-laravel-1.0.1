<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AffiliateCommission extends Model
{
    protected $fillable = [
        'reseller_user_id', 'referred_user_id', 'source_payment_id',
        'source_payment_type', 'gross_payment_amount', 'commission_percent',
        'commission_amount', 'currency', 'commission_status',
        'earned_at', 'payable_at', 'paid_at', 'payout_batch_id',
    ];

    protected function casts(): array
    {
        return [
            'gross_payment_amount' => 'decimal:2',
            'commission_amount' => 'decimal:2',
            'commission_percent' => 'decimal:2',
            'earned_at' => 'datetime',
            'payable_at' => 'datetime',
            'paid_at' => 'datetime',
        ];
    }

    public function reseller()
    {
        return $this->belongsTo(User::class, 'reseller_user_id');
    }

    public function resellerUser()
    {
        return $this->belongsTo(User::class, 'reseller_user_id');
    }

    public function referredUser()
    {
        return $this->belongsTo(User::class, 'referred_user_id');
    }

    public function approve(): void
    {
        $this->update(['commission_status' => 'approved']);
    }

    public function markPaid(): void
    {
        $this->update(['commission_status' => 'paid', 'paid_at' => now()]);
    }
}
