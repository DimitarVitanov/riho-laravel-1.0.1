<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AuthorityBuilderPage extends Model
{
    use HasFactory;

    protected $fillable = [
        'agency_profile_id',
        'source_type',
        'source_id',
        'source_title',
        'title',
        'slug',
        'location',
        'country',
        'content_sections',
        'property_images',
        'full_html',
        'scheduled_for',
        'status',
        'generation_started_at',
        'generation_completed_at',
        'published_at',
        'error_message',
        'meta_title',
        'meta_description',
    ];

    protected function casts(): array
    {
        return [
            'content_sections' => 'array',
            'property_images' => 'array',
            'scheduled_for' => 'date',
            'generation_started_at' => 'datetime',
            'generation_completed_at' => 'datetime',
            'published_at' => 'datetime',
        ];
    }

    public function agencyProfile(): BelongsTo
    {
        return $this->belongsTo(AgencyProfile::class);
    }

    /**
     * Get the source model (LocalSeoCampaign or GeneratedPage for AI Search)
     */
    public function source()
    {
        if ($this->source_type === 'local_seo') {
            return $this->belongsTo(LocalSeoCampaign::class, 'source_id');
        }
        
        return $this->belongsTo(GeneratedPage::class, 'source_id');
    }

    /**
     * Scope for pending pages that are due for generation
     */
    public function scopeDueForGeneration($query)
    {
        return $query->where('status', 'pending')
            ->where('scheduled_for', '<=', now()->toDateString());
    }

    /**
     * Check if page is ready to be generated
     */
    public function isDue(): bool
    {
        return $this->status === 'pending' && $this->scheduled_for <= now()->toDateString();
    }
}
