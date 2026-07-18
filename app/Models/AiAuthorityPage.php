<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AiAuthorityPage extends Model
{
    use HasFactory;

    protected static function booted()
    {
        static::created(function (AiAuthorityPage $page) {
            // Schedule Authority Builder page for the next day
            AuthorityBuilderPage::create([
                'agency_profile_id' => $page->agency_profile_id,
                'source_type' => 'ai_search',
                'source_id' => $page->id,
                'source_title' => $page->name,
                'title' => $page->name . ' - Real Estate Analysis',
                'slug' => \Illuminate\Support\Str::slug($page->name . '-analysis'),
                'location' => $page->target_city,
                'country' => $page->country,
                'scheduled_for' => now()->addDay()->toDateString(),
                'status' => 'pending',
            ]);
        });
    }

    protected $fillable = [
        'agency_profile_id',
        'agency_listing_id',
        'name',
        'slug',
        'target_city',
        'target_neighborhood',
        'country',
        'latitude',
        'longitude',
        'property_type',
        'page_type',
        'status',
        'published_at',
        'ai_generated_content',
        'meta_title',
        'meta_description',
        'schema_markup',
        'view_count',
    ];

    protected function casts(): array
    {
        return [
            'ai_generated_content' => 'array',
            'schema_markup' => 'array',
            'published_at' => 'datetime',
        ];
    }

    public function agencyProfile()
    {
        return $this->belongsTo(AgencyProfile::class);
    }

    public function listing()
    {
        return $this->belongsTo(AgencyListing::class, 'agency_listing_id');
    }

    public function generatedPage()
    {
        return $this->morphOne(GeneratedPage::class, 'pageable');
    }

    public function scopePublished($query)
    {
        return $query->where('status', 'published');
    }

    public function scopeDraft($query)
    {
        return $query->where('status', 'draft');
    }

    public function getPublicUrlAttribute(): string
    {
        return url($this->slug);
    }
}
