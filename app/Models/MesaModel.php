<?php
namespace App\Models;
use CodeIgniter\Model;
class MesaModel extends Model
{
    protected $table = 'mesa';
    protected $primaryKey = 'id';
    protected $allowedFields = ['id', 'codigo', 'secreto', 'descripcion'];
    protected $returnType    = \App\Entities\Mesa::class;
}