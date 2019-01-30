<?php

namespace App\Component\Utility\Database;

interface DbUtilityInterface
{
    /**
     * @param string $method
     * @param mixed $args
     *
     * @return mixed
     */
    public function dispatch($method, $args);
}
