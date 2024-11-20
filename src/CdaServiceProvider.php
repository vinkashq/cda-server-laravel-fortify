<?php

namespace Vinkas\Cda;

use Illuminate\Support\ServiceProvider;

class CdaServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->loadRoutesFrom(__DIR__.'/routes/web.php');

        $this->publishes([
          __DIR__.'/../database/migrations/2024_11_20_000000_create_cda_clients_table.php' => database_path('migrations/2024_11_20_000000_create_cda_clients_table.php'),
      ], 'cda-migrations');
    }

    public function register()
    {
      //
    }
}
