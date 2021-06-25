<?php

namespace Dizatech\Identifier;

use Dizatech\Identifier\Facades\NotifierLoginFacade;
use Dizatech\Identifier\Repositories\NotifierLoginRepository;
use Dizatech\Identifier\View\Components\LoginComponent;
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
        NotifierLoginFacade::shouldProxyTo(NotifierLoginRepository::class);
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
             __DIR__.'/config/dizatech_identifier.php' =>config_path('dizatech_identifier.php'),
             __DIR__.'/views/' => resource_path('views/vendor/dizatech-identifier'),
             __DIR__.'/assets/js/' => resource_path('js/vendor/dizatech-identifier'),
             __DIR__.'/assets/sass/' => resource_path('sass/vendor/dizatech-identifier'),
         ], 'dizatech_identifier');

         $this->loadViewComponentsAs('', [
             LoginComponent::class
         ]);
     }
}
