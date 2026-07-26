<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CompetitorDailyReportMetric extends Model
{
    use HasFactory;

    protected $fillable = [
        'competitor_daily_report_id',
        'new_properties',
        'removed_properties',
        'price_increases',
        'price_decreases',
        'new_seo_pages',
        'new_blog_posts',
        'new_reviews',
        'new_mentions',
        'total_changes',
    ];

    protected $casts = [
        'new_properties' => 'integer',
        'removed_properties' => 'integer',
        'price_increases' => 'integer',
        'price_decreases' => 'integer',
        'new_seo_pages' => 'integer',
        'new_blog_posts' => 'integer',
        'new_reviews' => 'integer',
        'new_mentions' => 'integer',
        'total_changes' => 'integer',
    ];

    public function report(): BelongsTo
    {
        return $this->belongsTo(CompetitorDailyReport::class, 'competitor_daily_report_id');
    }

    public function getPriceChangesCount(): int
    {
        return $this->price_increases + $this->price_decreases;
    }
}
