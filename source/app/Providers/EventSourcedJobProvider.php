<?php

namespace App\Providers;

use App\Jobs\Store\StoreDataJob;
use Illuminate\Support\ServiceProvider;

/**
 * Class EventSourcedJobProvider
 *
 * @package App\Providers
 */
class EventSourcedJobProvider extends ServiceProvider
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
        $this->app->bind('job.store', function ($app) {
            return new StoreDataJob($app);
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
            'job.store',
        ];
    }

}