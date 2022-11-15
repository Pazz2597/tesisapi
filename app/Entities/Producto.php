<?php

namespace App\Entities;

use CodeIgniter\Entity\Entity;

class Producto extends Entity
{
    protected $dates   = ['created_at', 'updated_at', 'deleted_at'];
    protected $casts = [
        'options'        => 'array',
        'options_object' => 'json',
        'options_array'  => 'json-array',
        'precio'         => 'float'
    ];
    protected $attributes = [
        'id'                => null,
        'nombre'            => null,
        'precio'           => null,
        'descripcion'       => null,
    ];
}