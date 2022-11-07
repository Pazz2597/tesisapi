<?php
namespace App\Models;
use CodeIgniter\Model;
class TokenModel extends Model
{
    protected $table = 'token';
    protected $primaryKey = 'id';
    protected $allowedFields = ['inicio', 'fin', 'token', 'id_mesa'];
    protected $returnType    = \App\Entities\Token::class;
}