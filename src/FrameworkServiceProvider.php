<?php

namespace Afterflow\Framework;

use Afterflow\Framework\Console\Commands\ScaffoldCommand;
use Illuminate\Support\ServiceProvider;

class FrameworkServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     */
    public function boot()
    {
        if ($this->app->runningInConsole()) {

            $this->commands([ScaffoldCommand::class]);

        }
    }

    /**
     * Register the application services.
     */
    public function register()
    {
    }
}
