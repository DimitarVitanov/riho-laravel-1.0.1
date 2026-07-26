<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote')->hourly();

Schedule::command('queue:work --stop-when-empty --max-time=55')
    ->everyMinute()
    ->withoutOverlapping()
    ->runInBackground();

Schedule::command('app:verify-agency-domains')
    ->everyMinute()
    ->withoutOverlapping()
    ->runInBackground();

Schedule::command('app:monitor-agency-dns-disconnections')
    ->everyFiveMinutes()
    ->withoutOverlapping()
    ->runInBackground();

Schedule::command('app:refresh-agency-sitemaps')
    ->daily()
    ->withoutOverlapping()
    ->runInBackground();

Schedule::command('ai:refresh-content --days=30')
    ->daily()
    ->at('03:00')
    ->withoutOverlapping()
    ->runInBackground();

Schedule::command('pages:process-scheduled')
    ->daily()
    ->at('06:00')
    ->withoutOverlapping()
    ->runInBackground();

Schedule::command('authority:generate')
    ->daily()
    ->at('07:00')
    ->withoutOverlapping()
    ->runInBackground();

// ============================================
// COMPETITOR INTELLIGENCE - 3-LEVEL SCANNING
// ============================================

// LEVEL 1 - Lightweight discovery (every 2 hours)
// Checks sitemaps, RSS, homepage for change signals
Schedule::command('competitor:discover')
    ->everyTwoHours()
    ->withoutOverlapping()
    ->runInBackground();

// LEVEL 2 - Deep scan (daily at 1am)
// Full property extraction, snapshot comparison
Schedule::command('competitor:scan-all')
    ->dailyAt('01:00')
    ->withoutOverlapping()
    ->runInBackground();

// LEVEL 3 - AI analysis & daily report (daily at 5am)
// Analyzes all changes, generates daily intelligence report
Schedule::command('competitor:daily-report')
    ->dailyAt('05:00')
    ->withoutOverlapping()
    ->runInBackground();
