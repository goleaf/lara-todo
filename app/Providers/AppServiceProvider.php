<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;

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
        $dummy = new class {
            public function getId()
            {
                return 'mary-' . uniqid();
            }
            public function getContext()
            {
                return [];
            }
            public function getPublicPropertiesDefinedBySubClass()
            {
                return [];
            }
            public function hasMethod($method)
            {
                return false;
            }
            public function call($method)
            {
            }
        };
        View::share('__livewire', $dummy);
        View::share('_instance', $dummy);
    }
}
