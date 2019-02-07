<?php

namespace App\Component\Utility\Database;

use Illuminate\Support\Facades\DB;

class DbInsert implements DbInsertInterface
{
    /**
     * @var \Illuminate\Support\Facades\DB
     */
    private $db;

    /**
     * @var \Illuminate\Database\Query\Builder
     */
    private $query;

    private $id;

    public function __construct($table, $id = null)
    {
        $this->db = new DB();
        $this->query = $this->db::table($table);
        $this->id = $id;
    }

    public function dispatch($method, $args)
    {
        // TODO: Implement dispatch() method.
        $this->$method($args);
        return $this->id;
    }

    protected function create($args)
    {
        $this->query->insert($args['values']);
        $this->id = $args['values']['id'];
    }

    protected function update($args)
    {
        $this->query->where('id', $args['id'])
            ->update($args['values']);
        $this->id = $args['id'];
    }
}
