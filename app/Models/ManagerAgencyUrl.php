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
        'commission_percent',
        'commission_amount',
        'commission_status',
        'commission_paid_at',
        'notes',
    ];

    protected $casts = [
        'commission_percent' => 'decimal:2',
        'commission_amount' => 'decimal:2',
        'commission_paid_at' => 'datetime',
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
