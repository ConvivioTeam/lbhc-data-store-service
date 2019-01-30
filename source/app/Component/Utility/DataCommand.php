<?php

namespace App\Component\Utility;

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

    protected $method;

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

    private $validFields = [
        'id' => 'id',
        'name' => 'name',
        'published' => 'published',
        'venue_id' => 'venue_id',
        'contact_id' => 'contact_id',
        'created' => 'created',
        'updated' => 'updated',
        'flagged' => 'flagged',
    ];

    public function __construct(Application $app, $command)
    {
        $this->app = $app;
        $this->command = $command;
//        Log::debug(print_r($this->command, true), [__CLASS__]);
        $this->setTable($this->getCommandItem('type'));
        $this->method = $this->getCommandItem('method', 'create');
        $this->dbInsert = new DbInsert($this->table);
        $this->id = uniqid('', true);
    }

    public function dispatch()
    {
        $params = $this->command;
        $validFields = $this->validFields;
        if ($this->method == 'update') {
            unset($validFields['created']);
            unset($validFields['id']);
        }
        $params['values'] = array_intersect_key(array_merge($params['values'], $this->defaultInsertValues()), $validFields);
        Log::debug(print_r($params, true), [__METHOD__]);
        $this->dbInsert = new DbInsert($this->table, $this->getCommandItem('id'));
        $this->dbInsert->dispatch($this->method, $params);
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
        $this->dbSelect = new DbSelect($this->table);
        $this->response = $this->dbSelect->dispatch('getById', $this->id);
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
