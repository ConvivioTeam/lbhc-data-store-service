<?php

namespace App\Component\Utility\Database;

interface DbSelectInterface
{
    /**
     * @param string $method
     *
     * @return mixed
     */
    public function dispatch($method);
}
