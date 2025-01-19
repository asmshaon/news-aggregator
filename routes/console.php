<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote')->everyMinute();

Schedule::command('news-aggregator:guardian-api:scrape')->hourly();
Schedule::command('news-aggregator:news-api:scrape')->hourly();
Schedule::command('news-aggregator:ny-times-api:scrape')->hourly();
