<?php

use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Schedule::call(function () {
    Artisan::call('migrate:fresh', [
        '--seed' => true,
        '--force' => true // required to run in production
    ]);
})->name('min_task_migrate_fresh_seed')
    ->withoutOverlapping()
    ->daily();

