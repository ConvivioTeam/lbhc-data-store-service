<?php

namespace App\Jobs\Data;

use App\Jobs\Job;
use Laravel\Lumen\Application;
use Rapide\LaravelQueueKafka\Queue\Jobs\KafkaJob;

/**
 * Queue job to get data from a query. I.e. GET request data handler.
 *
 * @package App\Jobs\Data
 */
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
        $params = [
            $data,
            $this->job->getJobId()
        ];
        /** @var \App\Component\Utility\DataQuery $dataQuery */
        $dataQuery = $this->app->makeWith('data.query', $params);
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
