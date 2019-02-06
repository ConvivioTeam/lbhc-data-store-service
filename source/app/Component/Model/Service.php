<?php

namespace App\Component\Model;

use Illuminate\Support\Facades\Log;
use Laravel\Lumen\Application;

class Service extends AbstractModel
{

    protected $validFields = [
        'id' => 'id',
        'name' => 'name',
        'description' => 'description',
        'provider_id' => 'provider_id',
        'event_id' => 'event_id',
        'eligibility_id' => 'eligibility_id',
        'costoption_id' => 'costoption_id',
        'created' => 'created',
        'updated' => 'updated',
        'flagged' => 'flagged',
    ];

    protected $requiredFields = [
        'id' => 'id',
        'name' => 'name',
        'provider_id' => 'provider_id',
        'created' => 'created',
        'updated' => 'updated',
        'flagged' => 'flagged',
    ];
}
