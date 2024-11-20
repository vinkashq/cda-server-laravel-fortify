<?php

namespace Vinkas\Cda\Server;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;
use Laravel\Fortify\Fortify;

class CdaServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->configureRoutes();

        $this->publishes([
          __DIR__.'/../database/migrations/2024_11_20_000000_create_cda_clients_table.php' => database_path('migrations/2024_11_20_000000_create_cda_clients_table.php'),
      ], 'cda-migrations');
    }

    public function register()
    {
      //
    }

    /**
     * Configure the routes offered by the application.
     *
     * @return void
     */
    protected function configureRoutes()
    {
        if (Fortify::$registersRoutes) {
            Route::group([
                'namespace' => 'Vinkas\Cda\Server\Http\Controllers',
                'domain' => config('fortify.domain', null),
                'prefix' => config('fortify.prefix'),
            ], function () {
                $this->loadRoutesFrom(__DIR__.'/../routes/routes.php');
            });
        }
    }
}
