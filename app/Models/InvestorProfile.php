<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class InvestorProfile extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'investor_type',
        'citizenship_country',
        'residence_country',
        'eligible_structure',
        'accreditation_status',
        'kyc_status',
        'aml_status',
        'max_commitment_amount',
        'preferred_currency',
        'payout_method',
        'payout_details_encrypted',
        'tax_form_status',
        'investor_notes',
        'risk_acknowledgement_at',
        'terms_accepted_at',
        'assigned_manager_id',
    ];

    protected function casts(): array
    {
        return [
            'risk_acknowledgement_at' => 'datetime',
            'terms_accepted_at' => 'datetime',
        ];
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function manager()
    {
        return $this->belongsTo(User::class, 'assigned_manager_id');
    }

    public function investments()
    {
        return $this->hasMany(InvestorInvestment::class);
    }

    public function capitalCalls()
    {
        return $this->hasManyThrough(CapitalCall::class, InvestorInvestment::class);
    }

    public function payouts()
    {
        return $this->hasMany(InvestorPayout::class, 'investor_user_id', 'user_id');
    }
}
