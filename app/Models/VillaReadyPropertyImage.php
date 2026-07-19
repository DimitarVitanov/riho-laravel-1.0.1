<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class VillaReadyPropertyImage extends Model
{
    use HasFactory;

    protected $fillable = [
        'villa_ready_property_id',
        'image_path',
        'image_type',
        'caption',
        'sort_order',
    ];

    public function property(): BelongsTo
    {
        return $this->belongsTo(VillaReadyProperty::class, 'villa_ready_property_id');
    }

    public function getImageUrlAttribute(): string
    {
        if (str_starts_with($this->image_path, 'http')) {
            return $this->image_path;
        }
        return asset('storage/' . $this->image_path);
    }
}
