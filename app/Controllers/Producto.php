<?php
namespace App\Controllers;
use App\Entities\Token;
use App\Models\ProductoModel;
use App\Models\TokenModel;
use CodeIgniter\RESTful\ResourceController;
use CodeIgniter\API\ResponseTrait;
use App\Models\MesaModel;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class Producto extends ResourceController
{
    use ResponseTrait;

    public function create()
    {
        $response['success'] = false;
        $model = new ProductoModel();
        $data = $this->request->getJSON(true);
        $producto =  new \App\Entities\Producto();
        $producto->fill($data);
        $model->save($producto);
        $response['success'] = true;
        $response['producto']=$model->find;
        return $this->respond($response);
    }
    public function index()
    {
        $model = new ProductoModel();
        $data = $model->findAll();
        return $this->respond($data);
    }
    public function update($id = null)
    {
        $response['success'] = false;
        $model = new ProductoModel();
        $data = $this->request->getJSON(true);
        $entity =  new \App\Entities\Producto();
        $entity->fill($data);
        $model->update($id, $entity);
        $response['success'] = true;
        return $this->respond($response);
    }
    public function delete($id = null)
    {
        $model = new ProductoModel();
        $response['success'] = false;
        $model->delete($id, true);
        $response['success'] = true;
        return $this->respond($response);
    }
    
}
