<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ManagerAgencyUrl extends Model
{
    use HasFactory;

    protected $fillable = [
        'manager_id',
        'url',
        'agency_profile_id',
        'status',
        'notes',
    ];

    /**
     * Get the manager (user) who owns this URL.
     */
    public function manager(): BelongsTo
    {
        return $this->belongsTo(User::class, 'manager_id');
    }

    /**
     * Get the agency profile linked to this URL (if matched).
     */
    public function agencyProfile(): BelongsTo
    {
        return $this->belongsTo(AgencyProfile::class);
    }
}
