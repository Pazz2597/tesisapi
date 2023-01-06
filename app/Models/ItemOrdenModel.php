<?php
namespace App\Models;
use CodeIgniter\Model;
class ItemOrdenModel extends Model
{
    protected $table = 'orden_item';
    protected $primaryKey = 'id';
    protected $allowedFields = ['id', 'id_producto', 'id_orden', 'cantidad'];
    protected $returnType    = \App\Entities\ItemOrden::class;
}