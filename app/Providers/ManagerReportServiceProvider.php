<?php

namespace App\Providers;

use App\Services\ManagerReportService;
use Illuminate\Support\ServiceProvider;

class ManagerReportServiceProvider extends ServiceProvider
{

    public function register()
    {
        $this->app->singleton(ManagerReportService::class, function ($app) {
            return new ManagerReportService();
        });
    }

    public function boot(): void
    {
        //
    }
}
