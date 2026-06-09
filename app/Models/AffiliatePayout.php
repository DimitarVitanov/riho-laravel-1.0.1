<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AffiliatePayout extends Model
{
    protected $fillable = [
        'reseller_user_id', 'payout_batch_month', 'amount', 'currency',
        'payout_method', 'payout_status', 'minimum_payout_reached',
        'scheduled_for', 'paid_at', 'transaction_reference', 'admin_note',
    ];

    protected function casts(): array
    {
        return [
            'amount' => 'decimal:2',
            'minimum_payout_reached' => 'boolean',
            'scheduled_for' => 'date',
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
}
