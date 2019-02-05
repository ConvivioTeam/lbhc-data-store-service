<?php

namespace App\Jobs\Data;

use App\Jobs\Job;
use Illuminate\Support\Facades\Log;
use Laravel\Lumen\Application;
use Rapide\LaravelQueueKafka\Queue\Jobs\KafkaJob;

class GetDataJob extends Job
{
    /**
     * Laravel application container.
     *
     * @var \Laravel\Lumen\Application
     */
    protected $app;

    /**
     * @var KafkaJob
     */
    protected $job;

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
        $this->job = $job;
        $data = $job->payload()['data'];
//        Log::debug(print_r($data, true), [__METHOD__]);
        /** @var \App\Component\Utility\DataQuery $dataQuery */
        $dataQuery = $this->app->makeWith('data.query', $data);
        $dataQuery->dispatch();
        $this->delete();
        return;
    }

    /**
     * Delete and item from the queue.
     *
     * @return void
     */
    public function delete()
    {
        $this->job->delete();
    }
}
