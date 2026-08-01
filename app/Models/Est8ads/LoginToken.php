<?php

namespace App\Models\Est8ads;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LoginToken extends Model
{
    protected $table = 'est8ads_login_tokens';

    protected $fillable = ['token_hash', 'user_id', 'expires_at', 'used_at', 'requested_ip'];

    protected $hidden = ['token_hash'];

    protected function casts(): array
    {
        return ['expires_at' => 'datetime', 'used_at' => 'datetime'];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function isValid(): bool
    {
        return $this->used_at === null && $this->expires_at->isFuture();
    }
}
