<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class GlobalAiPrompt extends Model
{
    use HasFactory;

    protected $fillable = [
        'feature_key',
        'label',
        'prompt_text',
        'ai_model_provider',
        'ai_model_name',
        'parameters_json',
        'is_active',
        'updated_by_user_id',
    ];

    protected $casts = [
        'parameters_json' => 'array',
        'is_active' => 'boolean',
    ];

    public function updatedBy()
    {
        return $this->belongsTo(User::class, 'updated_by_user_id');
    }

    public static function getPromptForFeature(string $featureKey): ?self
    {
        return static::where('feature_key', $featureKey)->where('is_active', true)->first();
    }
}
