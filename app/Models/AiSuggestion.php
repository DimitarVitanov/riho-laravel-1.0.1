<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class AiSuggestion extends Model
{
    use HasFactory;

    protected $fillable = [
        'agency_profile_id',
        'feature_key',
        'suggestion_type',
        'title',
        'target_keyword',
        'content_html',
        'content_json',
        'ai_summary',
        'ai_conclusion',
        'status',
        'reviewed_at',
        'reviewed_by_user_id',
        'reviewer_notes',
        'converted_to_page_id',
        'content_uniqueness_status',
        'uniqueness_checked_at',
    ];

    protected $casts = [
        'content_json' => 'array',
        'reviewed_at' => 'datetime',
        'uniqueness_checked_at' => 'datetime',
    ];

    public function agencyProfile()
    {
        return $this->belongsTo(AgencyProfile::class);
    }

    public function reviewer()
    {
        return $this->belongsTo(User::class, 'reviewed_by_user_id');
    }

    public function generatedPage()
    {
        return $this->belongsTo(GeneratedPage::class, 'converted_to_page_id');
    }

    public function scopePending($query)
    {
        return $query->where('status', 'pending')->whereNull('reviewed_at');
    }

    public function scopeNotReviewed($query)
    {
        return $query->whereNull('reviewed_at');
    }

    public function scopeAccepted($query)
    {
        return $query->where('status', 'accepted');
    }

    public function scopeActive($query)
    {
        return $query->whereIn('status', ['pending', 'accepted']);
    }

    public function isPending()
    {
        return $this->status === 'pending';
    }

    public function isAccepted()
    {
        return $this->status === 'accepted';
    }

    public function markAsAccepted($userId, $notes = null)
    {
        $this->update([
            'status' => 'accepted',
            'reviewed_at' => now(),
            'reviewed_by_user_id' => $userId,
            'reviewer_notes' => $notes,
        ]);
    }

    public function markAsSkipped($userId, $notes = null)
    {
        $this->update([
            'status' => 'skipped',
            'reviewed_at' => now(),
            'reviewed_by_user_id' => $userId,
            'reviewer_notes' => $notes,
        ]);
    }

    public function markAsRemoved($userId, $notes = null)
    {
        $this->update([
            'status' => 'removed',
            'reviewed_at' => now(),
            'reviewed_by_user_id' => $userId,
            'reviewer_notes' => $notes,
        ]);
    }

    public function markAsReviewed($userId)
    {
        $this->update([
            'reviewed_at' => now(),
            'reviewed_by_user_id' => $userId,
        ]);
    }
}
