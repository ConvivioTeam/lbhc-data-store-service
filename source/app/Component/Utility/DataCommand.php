<?php

namespace App\Component\Utility;

use App\Component\Model\AbstractModel;
use App\Component\Model\ModelConfigurationException;
use App\Component\Utility\Database\DbInsert;
use App\Component\Utility\Database\DbSelect;
use Illuminate\Support\Facades\Log;
use Laravel\Lumen\Application;

class DataCommand extends AbstractDataUtility
{
    /**
     * Laravel application container.
     *
     * @var \Laravel\Lumen\Application
     */
    protected $app;

    /**
     * @var array;
     */
    protected $command = [];

    /**
     * @var mixed|null
     */
    protected $method;

    /**
     * @var string
     */
    private $type;

    /**
     * @var string
     */
    private $table;

    /**
     * @var string
     */
    private $dateFormat = 'Y-m-d H:i:s';

    /**
     * @var string|null
     */
    private $id;

    /**
     * @var AbstractModel
     */
    protected $model;

    /**
     * @var \DateTime
     */

    /**
     * @var \App\Component\Utility\Database\DbInsert
     */
    protected $dbInsert;

    /**
     * @var \App\Component\Utility\Database\DbSelect
     */
    protected $dbSelect;

    /**
     * @var array
     */
    protected $params;


    public function __construct(Application $app, $command, $correlationId)
    {
        parent::__construct($app, $correlationId);
        $this->command = $command;
//        Log::debug(print_r($this->command, true), [__CLASS__]);
        $this->type = $this->getCommandItem('type');
        $this->setTable($this->type);
        $this->method = $this->getCommandItem('method', 'create');
        $this->dbInsert = new DbInsert($this->table);
        $this->id =  $this->method == 'update' ? $this->getCommandItem('id') : $this->correlationId;
        $modelAbstract = "model.{$this->type}";
        $this->model = $this->app->makeWith($modelAbstract);
        $this->validateModel();
    }

    protected function validateModel()
    {
        try {
            $this->model->validate();
        } catch (ModelConfigurationException $e) {
            $this->setHasError(true);
            $this->eventJob = 'job.api.error';
            $this->setError($e);
        }
    }

    protected function validateFields($fields)
    {
        try {
            $this->model->validateFields($fields);
        } catch (ModelConfigurationException $e) {
            $this->setHasError(true);
            $this->setError($e);
        }
    }

    public function dispatch()
    {
        if (!$this->hasError()) {
            $params = $this->command;
            $validFields = $this->model->getValidFields();
            if ($this->method == 'update') {
                unset($validFields['created']);
                unset($validFields['id']);
            }
            $params['values'] = array_intersect_key(
                array_merge(
                    $params['values'],
                    $this->defaultInsertValues()
                ),
                $validFields
            );
            $this->params = $params;
            $this->validateFields($this->params['values']);
            if (!$this->hasError()) {
                $this->dbInsert = new DbInsert($this->table, $this->getCommandItem('id'));
                $this->dbInsert->dispatch($this->method, $this->params);
            }
        }
        $this->produceEvent();
//        return $this->get($this->id);
    }

    /**
     * @return array
     *
     * @throws \Exception
     */
    protected function defaultInsertValues()
    {
        return [
            'id' => $this->id,
            'created' => $this->dateTime()->format($this->dateFormat),
            'updated' => $this->dateTime()->format($this->dateFormat),
        ];
    }

    public function getEventData()
    {
        $data = [
            'type' => $this->type,
            'id' => $this->id,
            'error' => false,
            'code' => 200,
            'message' => 'Found',
        ];
        if ($this->hasError()) {
            $data['data'] = [
                'code' => $this->error->getCode(),
                'message' => $this->error->getMessage(),
                'fields' => $this->params['values'],
            ];
        } else {
            $this->dbSelect = new DbSelect($this->table);
            $this->response = $this->dbSelect->dispatch('getById', $this->id);
            if (empty($this->response)) {
                $this->setHasError(true);
                $data['code'] = 404;
                $data['message'] = 'Not found';
                $data['error'] = true;
            }
            $data['data'] = $this->response;
        }
        return $data;
    }

    /**
     * @param string $table
     */
    public function setTable(string $table): void
    {
        $this->table = $table;
    }

    /**
     * @return string
     */
    public function getTable(): string
    {
        return $this->table;
    }

    /**
     * @param string $key
     * @param mixed|null $default
     *
     * @return mixed|null
     */
    protected function getCommandItem($key, $default = null)
    {
        return empty($this->command[$key]) ? $default : $this->command[$key];
    }

    /**
     * @return \DateTime
     *
     * @throws \Exception
     */
    private function dateTime()
    {
        if (!isset($this->dateTime)) {
            $tz = new \DateTimeZone('Europe/London');
            $this->dateTime = new \DateTime('now', $tz);
        }
        return $this->dateTime;
    }
}
