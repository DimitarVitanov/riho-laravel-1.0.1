<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ScheduledPageGeneration extends Model
{
    protected $fillable = [
        'agency_profile_id',
        'local_seo_campaign_id',
        'place_name',
        'place_type',
        'place_distance',
        'scheduled_for',
        'status',
        'error_message',
        'generated_page_id',
    ];
    
    protected $casts = [
        'scheduled_for' => 'date',
    ];
    
    public function agencyProfile()
    {
        return $this->belongsTo(AgencyProfile::class);
    }
    
    public function campaign()
    {
        return $this->belongsTo(LocalSeoCampaign::class, 'local_seo_campaign_id');
    }
    
    public function generatedPage()
    {
        return $this->belongsTo(GeneratedPage::class);
    }
    
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }
    
    public function scopeDueToday($query)
    {
        return $query->where('scheduled_for', '<=', now()->toDateString());
    }
}
