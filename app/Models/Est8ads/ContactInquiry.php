<?php

namespace App\Models\Est8ads;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ContactInquiry extends Model
{
    use SoftDeletes;

    protected $table = 'est8ads_contact_inquiries';
    protected $guarded = ['id'];

    protected function casts(): array
    {
        return [
            'phone' => 'encrypted', 'ip_address' => 'encrypted', 'metadata' => 'array',
            'consent_at' => 'datetime', 'responded_at' => 'datetime',
        ];
    }

    public function agency(): BelongsTo { return $this->belongsTo(Agency::class); }
    public function assignedUser(): BelongsTo { return $this->belongsTo(User::class, 'assigned_user_id'); }
}
