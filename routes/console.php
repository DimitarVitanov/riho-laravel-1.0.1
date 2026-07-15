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
