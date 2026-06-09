<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CapitalCall extends Model
{
    protected $fillable = [
        'investment_project_id', 'investor_investment_id', 'investor_user_id',
        'call_number', 'requested_amount', 'due_date', 'reason',
        'construction_phase', 'status', 'paid_amount', 'paid_at',
        'payment_reference', 'created_by_user_id',
    ];

    protected function casts(): array
    {
        return [
            'requested_amount' => 'decimal:2',
            'paid_amount' => 'decimal:2',
            'due_date' => 'date',
            'paid_at' => 'datetime',
        ];
    }

    public function project()
    {
        return $this->belongsTo(InvestmentProject::class, 'investment_project_id');
    }

    public function investment()
    {
        return $this->belongsTo(InvestorInvestment::class, 'investor_investment_id');
    }

    public function investor()
    {
        return $this->belongsTo(User::class, 'investor_user_id');
    }

    public function investorUser()
    {
        return $this->belongsTo(User::class, 'investor_user_id');
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by_user_id');
    }

    public function markAsPaid(string $reference = null): void
    {
        $this->update([
            'status' => 'paid',
            'paid_amount' => $this->requested_amount,
            'paid_at' => now(),
            'payment_reference' => $reference,
        ]);
    }
}
