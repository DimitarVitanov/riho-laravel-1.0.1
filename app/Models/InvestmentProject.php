<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InvestmentProject extends Model
{
    protected $fillable = [
        'project_name', 'project_code', 'project_location', 'project_country',
        'project_type', 'project_status', 'minimum_investment_amount',
        'target_raise_amount', 'max_raise_amount', 'preferred_return_percent',
        'preferred_return_type', 'rental_profit_share_percent',
        'project_exit_profit_share_percent', 'estimated_duration_months',
        'summary', 'full_description', 'risk_notes',
        'structure_us_llc_name', 'structure_uk_llp_name',
        'document_folder_path', 'cover_image_path',
    ];

    protected function casts(): array
    {
        return [
            'minimum_investment_amount' => 'decimal:2',
            'target_raise_amount' => 'decimal:2',
            'max_raise_amount' => 'decimal:2',
            'preferred_return_percent' => 'decimal:2',
            'rental_profit_share_percent' => 'decimal:2',
            'project_exit_profit_share_percent' => 'decimal:2',
        ];
    }

    public function investments()
    {
        return $this->hasMany(InvestorInvestment::class);
    }

    public function capitalCalls()
    {
        return $this->hasMany(CapitalCall::class);
    }

    public function investors()
    {
        return $this->hasManyThrough(User::class, InvestorInvestment::class, 'investment_project_id', 'id', 'id', 'investor_user_id');
    }
}
