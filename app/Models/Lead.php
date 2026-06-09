<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Lead extends Model
{
    use HasFactory;

    protected $fillable = [
        'agency_profile_id',
        'source',
        'first_name',
        'last_name',
        'email',
        'phone',
        'country',
        'investor_type',
        'interest_amount',
        'message',
        'landing_page_url',
        'status',
        'notes',
        'assigned_to_user_id',
    ];

    public function agencyProfile()
    {
        return $this->belongsTo(AgencyProfile::class);
    }

    public function assignedTo()
    {
        return $this->belongsTo(User::class, 'assigned_to_user_id');
    }

    public function getFullNameAttribute(): string
    {
        return trim("{$this->first_name} {$this->last_name}");
    }
}
