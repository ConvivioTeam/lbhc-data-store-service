<?php

namespace App\Component\Utility\Database;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

/**
 * Easy way to get data from the database.
 *
 * @package App\Component\Utility\Database
 */
class DbSelect implements DbSelectInterface
{
    /**
     * @var \Illuminate\Support\Facades\DB
     */
    private $db;

    /**
     * @var \Illuminate\Database\Query\Builder
     */
    private $query;

    private $limit = 50;

    public function __construct($table)
    {
        $this->db = new DB();
        $this->query = $this->db::table($table);
    }

    public function index()
    {
        $this->query->select(['*']);
    }

    public function getById($id)
    {
        $this->limit = 1;
        $this->query->select()->where('id', $id);
    }

    /**
     * @param string $method
     * @param mixed $args
     *
     * @return \Illuminate\Support\Collection|mixed
     */
    public function dispatch($method, $args = null)
    {
        $this->$method($args);
        $this->query->limit($this->limit);
        return $this->limit == 1 ? $this->get()->first() : $this->get()->all();
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function get()
    {
        return $this->query->get();
    }
}
