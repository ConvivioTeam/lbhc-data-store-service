<?php

namespace App\Jobs\Data;

use App\Jobs\Job;
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
     * @var KafkaJob
     */
    protected $job;

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
    public function fire(KafkaJob $job, $jobData)
    {
        $this->job = $job;
        $data = $job->payload()['data'];
        $data['method'] = 'create';
        $params = [
            $data,
            $this->job->getJobId()
        ];
        /** @var \App\Component\Utility\DataQuery $dataQuery */
        $dataQuery = $this->app->makeWith('data.command', $params);
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