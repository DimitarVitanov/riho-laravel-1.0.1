<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class GeneratedPage extends Model
{
    use HasFactory;

    protected $fillable = [
        'agency_profile_id',
        'feature_key',
        'title',
        'slug',
        'seo_title',
        'meta_description',
        'content_html',
        'content_json',
        'target_url',
        'content_uniqueness_status',
        'uniqueness_checked_at',
        'publish_workflow',
        'status',
        'published_at',
        'approved_by_user_id',
    ];

    protected $casts = [
        'content_json' => 'array',
        'uniqueness_checked_at' => 'datetime',
        'published_at' => 'datetime',
    ];

    public function agencyProfile()
    {
        return $this->belongsTo(AgencyProfile::class);
    }

    public function approvedBy()
    {
        return $this->belongsTo(User::class, 'approved_by_user_id');
    }

    public function scopePublished($query)
    {
        return $query->where('status', 'published');
    }

    public function scopePendingReview($query)
    {
        return $query->where('status', 'pending_review');
    }
}
