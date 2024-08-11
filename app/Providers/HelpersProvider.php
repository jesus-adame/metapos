<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Helpers\MathNumberHelper;

class HelpersProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        $this->app->bind('math_number.helper', function () {
            return new MathNumberHelper;
        });
    }
}
