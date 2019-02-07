<?php

namespace App\Providers;

use App\Component\Utility\DataCommand;
use App\Component\Utility\DataQuery;
use Illuminate\Support\ServiceProvider;

/**
 * CQRS service provider class.
 *
 * Commands (POST/PUT/DELETE) and queries (GET) should be handled differently.
 *
 * @package App\Providers
 */
class CQRSServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->registerDependencies();
    }

    /**
     * Register adapter dependencies in the container.
     */
    protected function registerDependencies()
    {
        $this->app->bind('data.command', function ($app, $params) {
            list($command, $jobId) = $params;
            return new DataCommand($app, $command, $jobId);
        });

        $this->app->bind('data.query', function ($app, $params) {
            list($query, $jobId) = $params;
            return new DataQuery($app, $query, $jobId);
        });
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return [
            'data.command',
            'data.query',
        ];
    }
}
