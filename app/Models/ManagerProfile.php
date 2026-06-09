<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ManagerProfile extends Model
{
    protected $fillable = [
        'user_id',
        'employee_code',
        'job_title',
        'department',
        'permission_level',
        'can_manage_agencies',
        'can_manage_investors',
        'can_review_ai_outputs',
        'can_prepare_payouts',
        'can_view_financials',
        'can_login_as_user',
        'daily_task_notes',
        'active_from',
        'active_until',
    ];

    protected function casts(): array
    {
        return [
            'can_manage_agencies' => 'boolean',
            'can_manage_investors' => 'boolean',
            'can_review_ai_outputs' => 'boolean',
            'can_prepare_payouts' => 'boolean',
            'can_view_financials' => 'boolean',
            'can_login_as_user' => 'boolean',
            'active_from' => 'date',
            'active_until' => 'date',
        ];
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function scopeActive($query)
    {
        return $query->where(function ($q) {
            $q->whereNull('active_until')->orWhere('active_until', '>=', now());
        });
    }
}
