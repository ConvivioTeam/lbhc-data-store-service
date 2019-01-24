<?php

namespace App\Component\Utility;

use Laravel\Lumen\Application;

class DataCommand
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
    protected $command;

    public function __construct(Application $app, $command)
    {
        $this->app = $app;
        $this->command = $command;
    }
}
