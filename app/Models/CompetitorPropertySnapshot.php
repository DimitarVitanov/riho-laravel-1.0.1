<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CompetitorPropertySnapshot extends Model
{
    use HasFactory;

    protected $fillable = [
        'competitor_property_id',
        'title',
        'price',
        'currency',
        'price_per_m2',
        'location_text',
        'property_type',
        'bedrooms',
        'bathrooms',
        'surface_m2',
        'plot_m2',
        'description',
        'images_json',
        'agent_name',
        'extraction_method',
        'field_confidence_json',
        'captured_at',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'price_per_m2' => 'decimal:2',
        'surface_m2' => 'decimal:2',
        'plot_m2' => 'decimal:2',
        'bedrooms' => 'integer',
        'bathrooms' => 'integer',
        'images_json' => 'array',
        'field_confidence_json' => 'array',
        'captured_at' => 'datetime',
    ];

    public function property(): BelongsTo
    {
        return $this->belongsTo(CompetitorProperty::class, 'competitor_property_id');
    }

    public function getFormattedPriceAttribute(): string
    {
        if (!$this->price) {
            return 'N/A';
        }

        $symbol = $this->currency === 'EUR' ? '€' : $this->currency;
        return $symbol . number_format($this->price, 0, ',', '.');
    }

    public function getImageCountAttribute(): int
    {
        return is_array($this->images_json) ? count($this->images_json) : 0;
    }

    public function getPriceChangeFrom(?CompetitorPropertySnapshot $previous): ?array
    {
        if (!$previous || !$this->price || !$previous->price) {
            return null;
        }

        if ($this->currency !== $previous->currency) {
            return null;
        }

        $diff = $this->price - $previous->price;
        if ($diff == 0) {
            return null;
        }

        $percentChange = ($diff / $previous->price) * 100;

        return [
            'old_price' => $previous->price,
            'new_price' => $this->price,
            'difference' => $diff,
            'percent_change' => round($percentChange, 2),
            'direction' => $diff > 0 ? 'increase' : 'decrease',
            'currency' => $this->currency,
        ];
    }
}
