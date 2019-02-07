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
     * @var string|null
     */
    protected $id;

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
     * @param mixed $query
     * @param string $correlationId
     *
     * @return void
     */
    public function __construct(Application $app, $query, $correlationId)
    {
        parent::__construct($app, $correlationId);
        $this->query = $query;
        $this->setQueryTable();
        $queryType = empty($this->query['queryType']) ? 'index' : $this->query['queryType'];
        $this->setQueryType($queryType);
        $this->dbSelect = new DbSelect($this->getQueryTable());
        $this->id =  $queryType == 'select' ? $this->query['id'] : null;
    }

    /**
     * Do the main work of this class.
     *
     * @return void
     */
    public function dispatch()
    {
        $this->response = $this->dbSelect->dispatch($this->getQueryType(), $this->query);
        if (empty($this->response)) {
            $this->setHasError(true);
        }
        $this->produceEvent();
    }

    /**
     * @return array
     */
    public function getEventData()
    {
        $eventData = [
            'query' => $this->query,
            'queryType' => $this->queryType,
            'type' => $this->getQueryTable(),
            'id' => $this->id,
            'data' => $this->response,
            'error' => false,
            'code' => 200,
            'message' => 'Found',
        ];
        if ($this->hasError()) {
            $eventData['code'] = 404;
            $eventData['message'] = 'Not found';
            $eventData['error'] = true;
        }
        if (is_object($eventData['data'])) {
            $eventData['data']->type = $this->getQueryTable();
        } elseif (is_array($eventData['data'])) {
            foreach ($eventData['data'] as $id => $item) {
                $eventData['data'][$id]->type = $this->getQueryTable();
            }
        }
        return $eventData;
    }

    /**
     * @param string $queryTable
     */
    public function setQueryTable(string $queryTable = null): void
    {
        if (!isset($queryTable)) {
            if (empty($this->query['type'])) {
                throw new InvalidArgumentException('Missing query table');
            }
            $queryTable = $this->query['type'];
        }
        $this->queryTable = $queryTable;
    }

    /**
     * @return mixed
     */
    public function getQueryTable()
    {
        return $this->query['type'];
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
}
