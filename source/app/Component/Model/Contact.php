<?php

namespace App\Component\Model;

class Contact extends AbstractModel
{
    protected $validFields = [
        'id' => 'id',
        'url' => 'url',
        'email' => 'email',
        'name' => 'name',
        'position' => 'position',
        'social_facebook' => 'social_facebook',
        'social_twitter' => 'social_twitter',
        'phonenumber' => 'phonenumber',
        'created' => 'created',
        'updated' => 'updated',
        'flagged' => 'flagged',
    ];
}
