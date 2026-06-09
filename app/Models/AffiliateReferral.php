<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AffiliateReferral extends Model
{
    protected $fillable = [
        'reseller_user_id', 'referral_code', 'clicked_ip_hash',
        'clicked_user_agent_hash', 'landing_url', 'referrer_url',
        'cookie_expires_at', 'converted_user_id', 'converted_account_type',
        'converted_at', 'status',
    ];

    protected function casts(): array
    {
        return [
            'cookie_expires_at' => 'datetime',
            'converted_at' => 'datetime',
        ];
    }

    public function reseller()
    {
        return $this->belongsTo(User::class, 'reseller_user_id');
    }

    public function resellerUser()
    {
        return $this->belongsTo(User::class, 'reseller_user_id');
    }

    public function convertedUser()
    {
        return $this->belongsTo(User::class, 'converted_user_id');
    }

    public function markConverted(User $user): void
    {
        $this->update([
            'converted_user_id' => $user->id,
            'converted_account_type' => $user->account_type,
            'converted_at' => now(),
            'status' => 'signed_up',
        ]);
    }
}
