<?php

namespace App\Component\Utility;

use Laravel\Lumen\Application;
use Rapide\LaravelQueueKafka\Queue\Connectors\KafkaConnector;

/**
 * Abstract utility class for data handling.
 *
 * @package App\Component\Utility
 */
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
    protected $correlationId;

    /**
     * @var string
     */
    public $eventJob = 'job.api.data';

    public $eventQueueProduce = 'api';

    /**
     * @var array
     */
    protected $response;

    /**
     * @var bool
     */
    protected $hasError = false;

    /**
     * @var \Exception|null
     */
    protected $error;


    public function __construct(Application $app, $correlationId)
    {
        $this->app = $app;
        $this->correlationId = $correlationId;
        $connector = new KafkaConnector($this->app);
        $this->queue = $connector->connect($this->getConfig());
        $this->queue->setCorrelationId($this->correlationId);
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

    /**
     * @return bool
     */
    public function hasError(): bool
    {
        return $this->hasError;
    }

    /**
     * @param bool $hasError
     */
    public function setHasError(bool $hasError): void
    {
        $this->hasError = $hasError;
        $this->eventJob = $hasError ? 'job.api.error' : 'job.api.data';
    }

    /**
     * @param \Exception|null $error
     */
    public function setError(?\Exception $error): void
    {
        $this->error = $error;
    }
}
