<?php

namespace Vinkas\Cda\Server\Tests;

use Illuminate\Database\Eloquent\Factories\Factory;
use Orchestra\Testbench\Concerns\WithWorkbench;
use Orchestra\Testbench\TestCase as Orchestra;
use Vinkas\Cda\Server\CdaServiceProvider;
use Orchestra\Testbench\Attributes\WithMigration; 
use Illuminate\Foundation\Testing\RefreshDatabase; 

#[WithMigration]
class TestCase extends Orchestra
{
    use RefreshDatabase;
    use WithWorkbench;

    protected function setUp(): void
    {
        parent::setUp();

        Factory::guessFactoryNamesUsing(
            fn (string $modelName) => 'Workbench\\Database\\Factories\\'.class_basename($modelName).'Factory'
        );
    }

    protected function getPackageProviders($app)
    {
        return [
            CdaServiceProvider::class,
        ];
    }

    /**
     * Define database migrations.
     *
     * @return void
     */
    protected function defineDatabaseMigrations() 
    {
        $this->loadMigrationsFrom(__DIR__.'/../database/migrations');
    }
}
