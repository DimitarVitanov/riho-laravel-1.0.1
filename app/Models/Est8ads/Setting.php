<?php

namespace App\Models\Est8ads;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Setting extends Model
{
    protected $table = 'est8ads_settings';
    protected $guarded = ['id'];
    protected $hidden = ['encrypted_value'];

    protected function casts(): array
    {
        return ['value' => 'array', 'encrypted_value' => 'encrypted', 'is_public' => 'boolean'];
    }

    public function agency(): BelongsTo { return $this->belongsTo(Agency::class); }
}
