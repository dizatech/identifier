<?php

namespace Dizatech\Identifier;

use Illuminate\Support\ServiceProvider;

class IdentifierServiceProvider extends ServiceProvider
{
    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
     public function boot()
     {
         $this->loadRoutesFrom(__DIR__ . '/routes/web.php');
         $this->loadViewsFrom(__DIR__ . '/views','dizatech_identifier');
         $this->loadMigrationsFrom(__DIR__ . '/database/migrations');
         $this->mergeConfigFrom(__DIR__ . '/config/dizatech_identifier.php', 'dizatech_identifier');
         $this->publishes([
             __DIR__.'/config/dizatech_identifier.php' =>config_path('dizatech_identifier.php')
         ], 'dizatech_identifier');

//         $this->loadViewComponentsAs('', [
//         ]);
     }
}
