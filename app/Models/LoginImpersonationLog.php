<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LoginImpersonationLog extends Model
{
    protected $fillable = [
        'admin_user_id', 'target_user_id', 'target_role',
        'reason', 'started_at', 'ended_at', 'ip_address', 'user_agent',
    ];

    protected function casts(): array
    {
        return [
            'started_at' => 'datetime',
            'ended_at' => 'datetime',
        ];
    }

    public function admin()
    {
        return $this->belongsTo(User::class, 'admin_user_id');
    }

    public function targetUser()
    {
        return $this->belongsTo(User::class, 'target_user_id');
    }
}
