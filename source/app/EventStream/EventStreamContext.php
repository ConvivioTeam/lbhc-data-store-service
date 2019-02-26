<?php

namespace App\EventStream;

use Enqueue\RdKafka\RdKafkaConnectionFactory;

class EventStreamContext
{

    /**
     * @var array
     */
    private $config;

    /**
     * @var array
     */
    private $defaultStreamConfig = [];

    /**
     * @var array
     */
    private $streamConfig = [];

    /**
     * @var \Enqueue\RdKafka\RdKafkaConnectionFactory
     */
    private $connectionFactory;

    /**
     * @var \Enqueue\RdKafka\RdKafkaContext
     */
    private $context;

    /**
     * EventStreamContext constructor.
     *
     * @param array $config
     */
    public function __construct($config = [])
    {
        $this->config = $config;
        $this->setStreamConfig();
        $this->makeConnectionFactory();
        $this->setContext();
    }

    private function setStreamConfig()
    {
        $this->defaultStreamConfig = [
            'global' => [
                'group.id' => config('connections.kafka.consumer_group_id'),
                'metadata.broker.list' => config('connections.kafka.brokers'),
                'enable.auto.commit' => 'false',
                'offset.store.method' => 'broker',
            ],
            'topic' => [
                'auto.offset.reset' => 'beginning',
            ],
        ];
        $this->streamConfig = array_merge($this->defaultStreamConfig, $this->config);
    }

    /**
     * @return array
     */
    public function getStreamConfig(): array
    {
        return $this->streamConfig;
    }

    private function makeConnectionFactory()
    {
        $this->connectionFactory = new RdKafkaConnectionFactory($this->getStreamConfig());
    }

    /**
     * @return void
     */
    private function setContext(): void
    {
        $this->context = $this->connectionFactory->createContext();
    }

    /**
     * @return \Enqueue\RdKafka\RdKafkaContext
     */
    public function context(): \Enqueue\RdKafka\RdKafkaContext
    {
        return $this->context;
    }
}
