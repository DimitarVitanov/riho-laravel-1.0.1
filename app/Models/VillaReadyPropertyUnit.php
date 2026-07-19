<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class VillaReadyPropertyUnit extends Model
{
    use HasFactory;

    protected $fillable = [
        'villa_ready_property_id',
        'building_number',
        'floor',
        'unit_code',
        'size_m2',
        'net_price',
        'status',
    ];

    protected $casts = [
        'size_m2' => 'decimal:2',
        'net_price' => 'decimal:2',
    ];

    public function property(): BelongsTo
    {
        return $this->belongsTo(VillaReadyProperty::class, 'villa_ready_property_id');
    }

    public function getFormattedPriceAttribute(): string
    {
        return '€' . number_format($this->net_price, 0, ',', '.');
    }

    public function getPricePerM2Attribute(): float
    {
        if ($this->size_m2 > 0) {
            return round($this->net_price / $this->size_m2, 2);
        }
        return 0;
    }
}
