<?php

namespace App\Entities;

use CodeIgniter\Entity\Entity;

class Token extends Entity
{
    protected $dates   = ['created_at', 'updated_at', 'deleted_at'];
    protected $casts = [
        'options'        => 'array',
        'options_object' => 'json',
        'options_array'  => 'json-array',
    ];
    protected $attributes = [
        'id'                => null,
        'inicio'            => null,
        'fin'           => null,
        'token'       => null,
        'id_mesa'       => null,
    ];
}