<?php

namespace App\Models\Est8ads;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PropertyMove extends Model
{
    use SoftDeletes;

    protected $table = 'est8ads_property_moves';
    protected $guarded = ['id'];

    protected function casts(): array
    {
        return [
            'target_date' => 'date', 'budget_min' => 'decimal:2', 'budget_max' => 'decimal:2',
            'requirements' => 'array', 'metadata' => 'array', 'submitted_at' => 'datetime', 'completed_at' => 'datetime',
        ];
    }

    public function profile(): BelongsTo { return $this->belongsTo(Profile::class); }
    public function agency(): BelongsTo { return $this->belongsTo(Agency::class); }
    public function assignedUser(): BelongsTo { return $this->belongsTo(User::class, 'assigned_user_id'); }
    public function properties(): HasMany { return $this->hasMany(Property::class); }
    public function matches(): HasMany { return $this->hasMany(MatchResult::class); }
    public function conversations(): HasMany { return $this->hasMany(Conversation::class); }
}
