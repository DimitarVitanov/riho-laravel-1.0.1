<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class VillaReadyPropertyReferral extends Model
{
    use HasFactory;

    protected $fillable = [
        'villa_ready_property_id',
        'agency_profile_id',
        'cookie_id',
        'visitor_email',
        'visitor_name',
        'visitor_ip',
        'visitor_user_agent',
        'first_visit_at',
        'last_visit_at',
        'cookie_expires_at',
        'status',
        'sale_amount',
        'commission_percent',
        'commission_amount',
        'paid_at',
        'admin_notes',
    ];

    protected $casts = [
        'first_visit_at' => 'datetime',
        'last_visit_at' => 'datetime',
        'cookie_expires_at' => 'datetime',
        'paid_at' => 'datetime',
        'sale_amount' => 'decimal:2',
        'commission_percent' => 'decimal:2',
        'commission_amount' => 'decimal:2',
    ];

    const STATUS_VISITED = 'visited';
    const STATUS_VIEWED = 'viewed';
    const STATUS_PAID = 'paid';

    public function property(): BelongsTo
    {
        return $this->belongsTo(VillaReadyProperty::class, 'villa_ready_property_id');
    }

    public function agencyProfile(): BelongsTo
    {
        return $this->belongsTo(AgencyProfile::class);
    }

    public function calculateCommission(): void
    {
        if ($this->sale_amount && $this->commission_percent) {
            $this->commission_amount = $this->sale_amount * ($this->commission_percent / 100);
        }
    }

    public function markAsPaid(float $saleAmount): void
    {
        $this->sale_amount = $saleAmount;
        $this->calculateCommission();
        $this->status = self::STATUS_PAID;
        $this->paid_at = now();
        $this->save();
    }

    public function markAsViewed(): void
    {
        $this->status = self::STATUS_VIEWED;
        $this->save();
    }

    public function getFormattedSaleAmountAttribute(): string
    {
        return $this->sale_amount ? '€' . number_format($this->sale_amount, 2) : '—';
    }

    public function getFormattedCommissionAttribute(): string
    {
        return $this->commission_amount ? '€' . number_format($this->commission_amount, 2) : '€0';
    }

    public function getStatusBadgeClassAttribute(): string
    {
        return match($this->status) {
            self::STATUS_VISITED => 'bg-secondary',
            self::STATUS_VIEWED => 'bg-primary',
            self::STATUS_PAID => 'bg-success',
            default => 'bg-secondary',
        };
    }
}
