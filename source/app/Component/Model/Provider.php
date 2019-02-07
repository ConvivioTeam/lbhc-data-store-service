<?php

namespace App\Component\Model;

class Provider extends AbstractModel
{
    protected $validFields = [
        'id' => 'id',
        'name' => 'name',
        'published' => 'published',
        'description' => 'description',
        'venue_id' => 'venue_id',
        'contact_id' => 'contact_id',
        'created' => 'created',
        'updated' => 'updated',
        'flagged' => 'flagged',
    ];
}
