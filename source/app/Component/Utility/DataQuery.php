<?php

namespace App\Component\Utility;

use App\Component\Utility\Database\DbSelect;
use Illuminate\Support\Facades\Log;
use InvalidArgumentException;
use Laravel\Lumen\Application;

class DataQuery extends AbstractDataUtility
{

    /**
     * Parameters for a database query.
     *
     * @var array
     */
    protected $query;

    /**
     * @var string
     */
    protected $queryTable;

    /**
     * @var string
     */
    protected $queryType;

    /**
     * @var \App\Component\Utility\Database\DbSelect
     */
    protected $dbSelect;

    /**
     * DataQuery constructor.
     *
     * @param \Laravel\Lumen\Application $app
     * @param $query
     *
     * @return void
     */
    public function __construct(Application $app, $query)
    {
        parent::__construct($app);
        $this->query = $query;
        $this->setQueryTable();
        $queryType = empty($this->query['type']) ? 'index' : $this->query['type'];
        $this->setQueryType($queryType);
        $this->dbSelect = new DbSelect($this->getQueryTable());
    }

    /**
     * Do the main work of this class.
     *
     * @return void
     */
    public function dispatch()
    {
        $this->response = $this->dbSelect->dispatch($this->getQueryType());
        $this->produceEvent();
    }

    /**
     * @return array
     */
    public function getEventData()
    {
        return [
            'query' => $this->query,
            'data' => $this->response->values(),
        ];
    }

    /**
     * @param string $queryTable
     */
    public function setQueryTable(string $queryTable = null): void
    {
        if (!isset($queryTable)) {
            if (empty($this->query['table'])) {
                throw new InvalidArgumentException('Missing query table');
            }
            $queryTable = $this->query['table'];
        }
        $this->queryTable = $queryTable;
    }

    /**
     * @return mixed
     */
    public function getQueryTable()
    {
        return $this->query['table'];
    }

    public function getQueryType()
    {
        return $this->queryType;
    }

    /**
     * Set the query type. E.g. index; select.
     *
     * @param string $queryType
     */
    public function setQueryType(string $queryType): void
    {
        $this->queryType = $queryType;
    }


    /**
     * Get the queue config.
     *
     * @return array
     */
//    protected function getConfig()
//    {
//        return [
//            'queue' => config('queue.connections.kafka.queue'),
//            'brokers' => config('queue.connections.kafka.brokers'),
//            'consumer_group_id' => config('queue.connections.kafka.consumer_group_id'),
//        ];
//    }
}
