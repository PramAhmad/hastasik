<?php

namespace App\Providers;

use App\Exceptions\CustomExceptionHandler;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->singleton(
            \Illuminate\Contracts\Debug\ExceptionHandler::class,
            CustomExceptionHandler::class
        );  
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        
    }
}
