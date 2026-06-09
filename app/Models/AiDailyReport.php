<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AiDailyReport extends Model
{
    protected $fillable = [
        'agency_profile_id', 'feature_key', 'report_date',
        'ai_actions_summary', 'completed_tasks_json',
        'recommended_next_actions_json', 'generated_content_ids_json',
        'detected_issues_json', 'usage_snapshot_json',
        'ai_model_used', 'token_input_count', 'token_output_count',
        'api_cost_estimate', 'status',
        'content_uniqueness_status', 'uniqueness_checked_at',
    ];

    protected function casts(): array
    {
        return [
            'report_date' => 'date',
            'completed_tasks_json' => 'array',
            'recommended_next_actions_json' => 'array',
            'generated_content_ids_json' => 'array',
            'detected_issues_json' => 'array',
            'usage_snapshot_json' => 'array',
            'api_cost_estimate' => 'decimal:4',
            'uniqueness_checked_at' => 'datetime',
        ];
    }

    public function agencyProfile()
    {
        return $this->belongsTo(AgencyProfile::class);
    }
}
