<?php
namespace App\Models;
use CodeIgniter\Model;
class ClienteModel extends Model

{
    protected $table = 'clientes';
    protected $primaryKey = 'id';
    protected $allowedFields = [ 'ci', 'nombAp', 'direccion','telefono','correo'];
    protected $returnType    = \App\Entities\Cliente::class;
}