<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

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
        \Illuminate\Support\Facades\Gate::policy(\App\Models\Pengampu::class, \App\Policies\PengampuPolicy::class);
        \Illuminate\Support\Facades\Gate::policy(\App\Models\Nilai::class, \App\Policies\NilaiPolicy::class);
    }
}
