<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CompetitorScanRun extends Model
{
    use HasFactory;

    protected $fillable = [
        'competitor_id',
        'level',
        'status',
        'started_at',
        'finished_at',
        'urls_scanned',
        'changes_detected',
        'errors_count',
        'error_log',
    ];

    protected $casts = [
        'started_at' => 'datetime',
        'finished_at' => 'datetime',
        'urls_scanned' => 'integer',
        'changes_detected' => 'integer',
        'errors_count' => 'integer',
    ];

    public function competitor(): BelongsTo
    {
        return $this->belongsTo(Competitor::class);
    }

    public function scopeSuccessful($query)
    {
        return $query->where('status', 'success');
    }

    public function scopeFailed($query)
    {
        return $query->where('status', 'failed');
    }

    public function scopeOfLevel($query, string $level)
    {
        return $query->where('level', $level);
    }

    public function getDurationAttribute(): ?int
    {
        if (!$this->started_at || !$this->finished_at) {
            return null;
        }

        return $this->started_at->diffInSeconds($this->finished_at);
    }

    public function getDurationForHumansAttribute(): ?string
    {
        $duration = $this->duration;
        if ($duration === null) {
            return null;
        }

        if ($duration < 60) {
            return $duration . 's';
        }

        $minutes = floor($duration / 60);
        $seconds = $duration % 60;

        return $minutes . 'm ' . $seconds . 's';
    }

    public function markAsRunning(): void
    {
        $this->update([
            'status' => 'running',
            'started_at' => now(),
        ]);
    }

    public function markAsSuccess(int $urlsScanned = 0, int $changesDetected = 0): void
    {
        $this->update([
            'status' => 'success',
            'finished_at' => now(),
            'urls_scanned' => $urlsScanned,
            'changes_detected' => $changesDetected,
        ]);
    }

    public function markAsFailed(string $error): void
    {
        $this->update([
            'status' => 'failed',
            'finished_at' => now(),
            'error_log' => $error,
            'errors_count' => $this->errors_count + 1,
        ]);
    }
}
