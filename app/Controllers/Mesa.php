<?php
namespace App\Controllers;
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
        $model = new MesaModel();
        $data['mesas'] = $model->findAll();
        return $this->respond($data);
    }
    
}
