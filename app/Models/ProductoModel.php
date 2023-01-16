<?php
namespace App\Models;
use CodeIgniter\Model;
class ProductoModel extends Model
{
    protected $table = 'producto';
    protected $primaryKey = 'id';
    protected $allowedFields = [ 'nombre', 'precio', 'descripcion','tipo','stock'];
    protected $returnType    = \App\Entities\Producto::class;
}