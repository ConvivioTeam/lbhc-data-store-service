<?php

namespace App\Providers;

use App\EventStream\EventStreamContext;
use Illuminate\Support\ServiceProvider;

class EventStreamProvider extends ServiceProvider
{
    /**
     * Register the service provider.
     */
    public function register()
    {
        $this->mergeConfigFrom(
            __DIR__ . '/../../config/kafka.php',
            'connections.kafka'
        );
        $this->registerDependencies();
    }

    /**
     * Register adapter dependencies in the container.
     */
    protected function registerDependencies()
    {
        $this->app->bind('eventstream.context', function ($app, $parameters) {
            $conf = empty($parameters['conf']) ? [] : $parameters['conf'];
            return new EventStreamContext($conf);
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
            'eventstream.context',
        ];
    }
}
