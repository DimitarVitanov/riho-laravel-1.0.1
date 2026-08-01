<?php

namespace App\Models\Est8ads;

use App\Models\AgencyProfile;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Agency extends Model
{
    use SoftDeletes;

    protected $table = 'est8ads_agencies';
    protected $guarded = ['id'];

    protected function casts(): array
    {
        return ['branding' => 'array', 'metadata' => 'array'];
    }

    public function agencyProfile(): BelongsTo { return $this->belongsTo(AgencyProfile::class); }
    public function profiles(): HasMany { return $this->hasMany(Profile::class); }
    public function propertyMoves(): HasMany { return $this->hasMany(PropertyMove::class); }
    public function properties(): HasMany { return $this->hasMany(Property::class); }
    public function chains(): HasMany { return $this->hasMany(Chain::class); }
    public function conversations(): HasMany { return $this->hasMany(Conversation::class); }
    public function members(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'est8ads_agency_memberships')->withPivot(['role', 'status', 'permissions'])->withTimestamps();
    }
}
