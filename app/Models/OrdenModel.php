<?php
namespace App\Models;
use CodeIgniter\Model;
class OrdenModel extends Model
{
    protected $table = 'orden';
    protected $primaryKey = 'id';
    protected $allowedFields = ['fecha', 'id_token', 'total', 'observaciones', 'estado'];
    protected $returnType    = \App\Entities\Orden::class;

    function guardar($orden){
        $this->save($orden);
        $insert_id = $this->db->insertID();     
        return  $insert_id;
     }
}