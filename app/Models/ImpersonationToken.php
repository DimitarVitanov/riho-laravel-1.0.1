<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ImpersonationToken extends Model
{
    protected $fillable = [
        'token',
        'admin_user_id',
        'target_user_id',
        'expires_at',
        'used_at',
    ];

    protected $casts = [
        'expires_at' => 'datetime',
        'used_at' => 'datetime',
    ];

    public function adminUser()
    {
        return $this->belongsTo(User::class, 'admin_user_id');
    }

    public function targetUser()
    {
        return $this->belongsTo(User::class, 'target_user_id');
    }

    public function isValid(): bool
    {
        return !$this->used_at && $this->expires_at->isFuture();
    }
}
