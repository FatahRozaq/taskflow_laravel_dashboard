<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use pxlrbt\FilamentExcel\FilamentExcelServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->register(FilamentExcelServiceProvider::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
