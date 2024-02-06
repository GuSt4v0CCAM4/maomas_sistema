<?php

namespace App\Providers;

use Carbon\Carbon;
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
        Carbon::setUTF8(true);
        Carbon::setLocale(config('es_PE'));
        setlocale(LC_TIME, 'es_PE.utf8', 'es_PE', 'es', 'es_ES.utf8', 'es_ES');
        config(['app.locale'=>'es_PE']);
    }
}
