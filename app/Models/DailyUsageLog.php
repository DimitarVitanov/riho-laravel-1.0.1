<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DailyUsageLog extends Model
{
    protected $fillable = [
        'agency_profile_id',
        'feature_key',
        'usage_date',
        'count',
    ];

    protected function casts(): array
    {
        return [
            'usage_date' => 'date',
        ];
    }

    public function agencyProfile()
    {
        return $this->belongsTo(AgencyProfile::class);
    }
}
