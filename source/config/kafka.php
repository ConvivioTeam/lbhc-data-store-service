<?php
/**
 * Kafka queue connection config.
 */

return [
    'connections' => [
        'kafka' => [
            'driver' => 'kafka',
            'queue' => env('KAFKA_QUEUE', 'store'),
            'consumer_group_id' => env('KAFKA_CONSUMER_GROUP_ID', 'dos_data_store'),
            'brokers' => env('KAFKA_BROKERS', 'localhost'),
            'sleep_on_error' => env('KAFKA_ERROR_SLEEP', 5),
            'sleep_on_deadlock' => env('KAFKA_DEADLOCK_SLEEP', 2),
        ],
    ],
];
