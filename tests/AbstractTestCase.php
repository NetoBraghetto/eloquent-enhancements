<?php

use Illuminate\Foundation\Application;
use Illuminate\Contracts\Config\Repository;
abstract class AbstractTestCase extends Orchestra\Testbench\TestCase
{
    /**
     * @param Application $app
     * @return void
     */
    protected function defineEnvironment($app): void
    {
        // Setup default database to use sqlite :memory:
        tap($app->make('config'), function (Repository $config) {
            $config->set('database.default', 'testbench');
            $config->set('database.connections.testbench', [
                'driver'   => 'sqlite',
                'database' => ':memory:',
                'prefix'   => '',
            ]);
        });
    }

    protected function defineDatabaseMigrations(): void
    {
        $this->loadMigrationsFrom(__DIR__ . '/database/migrations');
    }

    protected function defineDatabaseSeeders(): void
    {
        $this->artisan('db:seed');
    }
}
