<?php

namespace App\Component\Utility\Database;

use Illuminate\Support\Facades\DB;

/**
 * Easy way to get data from the database.
 *
 * @package App\Component\Utility\Database
 */
class DbSelect implements DbSelectInterface
{

    private $db;

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
        $this->query->limit($this->limit);
    }

    /**
     * @return \Illuminate\Support\Collection|mixed
     */
    public function dispatch($method)
    {
        $this->$method();
        return $this->get();
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function get()
    {
        return $this->query->get();
    }
}
