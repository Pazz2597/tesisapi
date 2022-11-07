<?php
namespace App\Controllers;
use App\Entities\Token;
use App\Models\TokenModel;
use CodeIgniter\RESTful\ResourceController;
use CodeIgniter\API\ResponseTrait;
use App\Models\MesaModel;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class Mesa extends ResourceController
{
    use ResponseTrait;
    
    public function index()
    {
        /*
        $model = new MesaModel();
        $data['mesas'] = $model->findAll();
        */
        helper('jwt');
        $model = new TokenModel();
        $userData = getUserFromRequest($this->request);
        $token = new Token();
        $token->token = rand(100000, 999999);
        $token->inicio = time();
        $token->fin = $token->inicio + 180;
        $token->id_mesa =
        $mesa_id = $userData->mesa_id;
        $model->save($token);
        return $this->respond($token);
    }
    
}
