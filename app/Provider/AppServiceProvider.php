<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Pagination\Paginator; // Tambahkan ini agar pagination bootstrap jalan

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Gunakan Bootstrap 5 untuk pagination (agar tampilan tabel rapi)
        Paginator::useBootstrapFive();
    }
}
