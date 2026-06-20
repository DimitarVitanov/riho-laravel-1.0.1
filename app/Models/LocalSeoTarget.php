<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class LocalSeoTarget extends Model
{
    use HasFactory;

    protected $fillable = [
        'agency_profile_id',
        'target_type',
        'target_value',
        'is_selected',
        'generated_page_id',
    ];

    protected function casts(): array
    {
        return [
            'is_selected' => 'boolean',
        ];
    }

    public function agencyProfile()
    {
        return $this->belongsTo(AgencyProfile::class);
    }

    public function generatedPage()
    {
        return $this->belongsTo(GeneratedPage::class);
    }

    public function scopeCities($query)
    {
        return $query->where('target_type', 'city');
    }

    public function scopeKeywords($query)
    {
        return $query->where('target_type', 'keyword');
    }

    public function scopeSubniches($query)
    {
        return $query->where('target_type', 'subniche');
    }

    public function scopeSelected($query)
    {
        return $query->where('is_selected', true);
    }
}
