<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schedule;
use App\Console\Commands\SendForUnsignedManagerNotifications;

class ScheduleServiceProvider extends ServiceProvider
{

    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        Schedule::command(SendForUnsignedManagerNotifications::class)
        ->weeklyOn(1, '8:00')
        ->when(function () {
            return now()->weekOfYear % 2 == 0;
        });
    }
}
