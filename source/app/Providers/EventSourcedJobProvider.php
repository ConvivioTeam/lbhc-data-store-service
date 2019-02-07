<?php

namespace App\Providers;

use App\Jobs\Data\GetDataJob;
use App\Jobs\Data\StoreDataJob;
use App\Jobs\Data\UpdateDataJob;
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
        $this->app->bind('job.data.store', function ($app) {
            return new StoreDataJob($app);
        });

        $this->app->bind('job.data.update', function ($app) {
            return new UpdateDataJob($app);
        });

        $this->app->bind('job.data.get', function ($app) {
            return new GetDataJob($app);
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
            'job.data.store',
            'job.data.update',
            'job.data.get',
        ];
    }
}
