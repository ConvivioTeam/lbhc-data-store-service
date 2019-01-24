<?php

namespace App\Providers;

use App\Component\Utility\DataCommand;
use App\Component\Utility\DataQuery;
use Illuminate\Support\ServiceProvider;

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
        $this->app->bind('data.command', function ($app, $command) {
            return new DataCommand($app, $command);
        });

        $this->app->bind('data.query', function ($app, $query) {
            return new DataQuery($app, $query);
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
