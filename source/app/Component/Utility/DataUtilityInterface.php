<?php

namespace App\Component\Utility;

interface DataUtilityInterface
{

    /**
     * @return void
     */
    public function dispatch();

    public function produceEvent();

    public function getEventData();
}
