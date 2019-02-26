<?php

namespace App\EventStream\Commands;

use Illuminate\Console\Command;

class EventStreamConsume extends Command
{

    protected $signature = 'eventstream:consume';

    protected $description = 'Consume items in the event stream';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {

    }
}
