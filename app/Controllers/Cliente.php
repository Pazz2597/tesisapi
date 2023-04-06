<?php

namespace App\Controllers;

use App\Models\ClienteModel;
use CodeIgniter\RESTful\ResourceController;
use CodeIgniter\API\ResponseTrait;

class Cliente extends ResourceController
{
    use ResponseTrait;
    public function buscar()
    {
        $json = $this->request->getJSON();
        $data=$json->ci;
        $model = new ClienteModel();
        $cliente=$model->where('ci',$data)->first();
        return $this->respond($cliente);
      
    }

    public function guardar()
    {
        $response['success'] = false;
        $model = new ClienteModel();
        $data = $this->request->getJSON(true);
        $cliente =  new \App\Entities\Cliente();
        $cliente->fill($data);
        $model->save($cliente);
        $response['success'] = true;
        $response['cliente']=$data;
        return $this->respond($response);

      
    }

    public function buscarId()
    {
        $json = $this->request->getJSON();
        $data=$json->ci;
        $model = new ClienteModel();
        $cliente=$model->where('id',$data)->first();
        return $this->respond($cliente);
      
    }

    
}
