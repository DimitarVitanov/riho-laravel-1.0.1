<?php

namespace App\Models\Est8ads;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Profile extends Model
{
    use SoftDeletes;

    protected $table = 'est8ads_profiles';

    protected $guarded = ['id'];

    protected $hidden = ['intake_token_hash'];

    protected function casts(): array
    {
        return [
            'phone' => 'encrypted',
            'address' => 'encrypted',
            'preferences' => 'array',
            'metadata' => 'array',
            'consent_at' => 'datetime',
            'last_seen_at' => 'datetime',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function agency(): BelongsTo
    {
        return $this->belongsTo(Agency::class);
    }

    public function propertyMoves(): HasMany
    {
        return $this->hasMany(PropertyMove::class);
    }

    public function invoices(): HasMany
    {
        return $this->hasMany(Invoice::class);
    }

    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class);
    }
}
