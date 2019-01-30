<?php

namespace App\Jobs\Data;

use App\Jobs\Job;
use Illuminate\Support\Facades\Log;
use Laravel\Lumen\Application;
use Rapide\LaravelQueueKafka\Queue\Jobs\KafkaJob;

/**
 * Class StoreDataJob
 *
 * @package App\Jobs\Store
 */
class StoreDataJob extends Job
{
    /**
     * Laravel application container.
     *
     * @var \Laravel\Lumen\Application
     */
    protected $app;

    /**
     * Array of job parameters.
     *
     * @var array
     */
    protected $parameters = [];

    /**
     * StoreDataJob constructor.
     *
     * @param \Laravel\Lumen\Application $app - Application container.
     *
     * @return void
     */
    public function __construct(Application $app)
    {
        $this->app = $app;
    }

    /**
     * Storage action.
     *
     * @param \Rapide\LaravelQueueKafka\Queue\Jobs\KafkaJob $job - Kafka Job object.
     *
     * @return void
     */
    public function fire(KafkaJob $job)
    {
        $data = $job->payload()['data'];
        $data['method'] = 'create';
        /** @var \App\Component\Utility\DataQuery $dataQuery */
        $dataQuery = $this->app->makeWith('data.command', $data);
        $dataQuery->dispatch();
        return;
    }

    /**
     * Delete and item from the queue.
     *
     * @return void
     */
    public function delete()
    {
        parent::delete();
    }


}