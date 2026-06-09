<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InvestorInvestment extends Model
{
    protected $fillable = [
        'investor_user_id', 'investor_profile_id', 'investment_project_id',
        'structure_used', 'committed_amount', 'funded_amount',
        'unfunded_commitment_amount', 'preferred_return_percent',
        'preferred_return_accrued_amount', 'rental_profit_accrued_amount',
        'project_exit_profit_accrued_amount', 'total_earnings_accrued',
        'total_payouts_paid', 'current_balance', 'investment_status',
        'signed_documents_status', 'started_at', 'exited_at',
    ];

    protected function casts(): array
    {
        return [
            'committed_amount' => 'decimal:2',
            'funded_amount' => 'decimal:2',
            'unfunded_commitment_amount' => 'decimal:2',
            'preferred_return_accrued_amount' => 'decimal:2',
            'rental_profit_accrued_amount' => 'decimal:2',
            'project_exit_profit_accrued_amount' => 'decimal:2',
            'total_earnings_accrued' => 'decimal:2',
            'total_payouts_paid' => 'decimal:2',
            'current_balance' => 'decimal:2',
            'started_at' => 'datetime',
            'exited_at' => 'datetime',
        ];
    }

    public function investor()
    {
        return $this->belongsTo(User::class, 'investor_user_id');
    }

    public function investorProfile()
    {
        return $this->belongsTo(InvestorProfile::class);
    }

    public function project()
    {
        return $this->belongsTo(InvestmentProject::class, 'investment_project_id');
    }

    public function capitalCalls()
    {
        return $this->hasMany(CapitalCall::class);
    }

    public function payouts()
    {
        return $this->hasMany(InvestorPayout::class);
    }

    public function calculateTotalEarnings(): float
    {
        return $this->preferred_return_accrued_amount
            + $this->rental_profit_accrued_amount
            + $this->project_exit_profit_accrued_amount;
    }
}
