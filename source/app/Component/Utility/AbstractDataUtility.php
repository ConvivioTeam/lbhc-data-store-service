<?php

namespace App\Component\Utility;


use Laravel\Lumen\Application;
use Rapide\LaravelQueueKafka\Queue\Connectors\KafkaConnector;

abstract class AbstractDataUtility implements DataUtilityInterface
{
    /**
     * Laravel application container.
     *
     * @var \Laravel\Lumen\Application
     */
    protected $app;

    /**
     * @var \Rapide\LaravelQueueKafka\Queue\KafkaQueue
     */
    protected $queue;

    /**
     * @var string
     */
    public $eventJob = 'job.api.data';

    public $eventQueueProduce = 'api';

    /**
     * @var \Illuminate\Support\Collection
     */
    protected $response;


    public function __construct(Application $app)
    {
        $this->app = $app;
        $connector = new KafkaConnector($this->app);
        $this->queue = $connector->connect($this->getConfig());
    }

    public function produceEvent()
    {
        $this->queue->push($this->eventJob, $this->getEventData(), $this->eventQueueProduce);
    }



    /**
     * Get the queue config.
     *
     * @return array
     */
    protected function getConfig()
    {
        return [
            'queue' => config('queue.connections.kafka.queue'),
            'brokers' => config('queue.connections.kafka.brokers'),
            'consumer_group_id' => config('queue.connections.kafka.consumer_group_id'),
        ];
    }
}