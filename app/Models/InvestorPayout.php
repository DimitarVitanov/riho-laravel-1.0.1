<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InvestorPayout extends Model
{
    protected $fillable = [
        'investor_user_id', 'investor_investment_id', 'investment_project_id',
        'payout_type', 'amount', 'currency', 'payout_method', 'payout_status',
        'scheduled_for', 'paid_at', 'transaction_reference', 'admin_note',
        'approved_by_admin_id',
    ];

    protected function casts(): array
    {
        return [
            'amount' => 'decimal:2',
            'scheduled_for' => 'date',
            'paid_at' => 'datetime',
        ];
    }

    public function investor()
    {
        return $this->belongsTo(User::class, 'investor_user_id');
    }

    public function investorUser()
    {
        return $this->belongsTo(User::class, 'investor_user_id');
    }

    public function investment()
    {
        return $this->belongsTo(InvestorInvestment::class, 'investor_investment_id');
    }

    public function project()
    {
        return $this->belongsTo(InvestmentProject::class, 'investment_project_id');
    }

    public function approvedBy()
    {
        return $this->belongsTo(User::class, 'approved_by_admin_id');
    }

    public function approve(int $adminId): void
    {
        $this->update([
            'payout_status' => 'approved',
            'approved_by_admin_id' => $adminId,
        ]);
    }

    public function markPaid(string $reference = null): void
    {
        $this->update([
            'payout_status' => 'paid',
            'paid_at' => now(),
            'transaction_reference' => $reference,
        ]);
    }
}
