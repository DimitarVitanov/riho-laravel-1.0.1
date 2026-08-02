<?php

namespace App\Models\Est8ads;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class InternetSource extends Model
{
    use SoftDeletes;

    protected $table = 'est8ads_internet_sources';
    protected $guarded = ['id'];
    protected $hidden = ['credentials_reference'];

    protected function casts(): array
    {
        return [
            'enabled' => 'boolean', 'configuration' => 'array', 'crawl_rules' => 'array',
            'country_codes' => 'array', 'last_crawled_at' => 'datetime',
            'last_success_at' => 'datetime', 'last_error_at' => 'datetime',
        ];
    }

    public function agency(): BelongsTo { return $this->belongsTo(Agency::class); }
    public function discoveryJobs(): HasMany { return $this->hasMany(DiscoveryJob::class); }
    public function externalListings(): HasMany { return $this->hasMany(ExternalListing::class); }

    public function isApprovedForDiscovery(): bool
    {
        $configuration = array_change_key_case((array) $this->configuration, CASE_LOWER);
        $containsCredential = array_intersect(['api_key', 'token', 'password', 'secret', 'credentials'], array_keys($configuration)) !== [];
        $referenceIsSafe = ! $this->credentials_reference || str_starts_with($this->credentials_reference, 'config:');

        return $this->enabled
            && $this->status === 'active'
            && in_array($this->access_method ?: $this->type, ['api', 'feed', 'sitemap'], true)
            && $this->terms_status === 'approved'
            && in_array($this->robots_status, ['allowed', 'not_applicable'], true)
            && ! $containsCredential
            && $referenceIsSafe;
    }
}
