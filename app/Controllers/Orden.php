<?php
namespace App\Controllers;
use App\Entities\Token;
use App\Models\ItemOrdenModel;
use App\Models\TokenModel;
use CodeIgniter\RESTful\ResourceController;
use CodeIgniter\API\ResponseTrait;
use App\Models\MesaModel;
use App\Models\OrdenModel;
use App\Models\ProductoModel;
use DateTime;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class Orden extends ResourceController
{
    use ResponseTrait;

    public function create()
    {
        $response['success'] = false;
        
        $data = $this->request->getJSON(true);
        
        if(!isset($data['productos']))
            return $this->respond(['error'=>'La variable productos es requerida.'], 400);
        if(count($data['productos']) == 0)
            return $this->respond(['error'=>'Sin productos.'], 400);
        if(!isset($data['token']))
            return $this->respond(['error'=>'Token no valido.'], 400);
        
        $tokenData = $data['token'];
        $tokenModel = new TokenModel();
        $token = $tokenModel->where('token', $tokenData)->orderBy('inicio', 'DESC')->first();
        
        if(!$token)
            return $this->respond(['error'=>'Token no existe.'], 400);
        
        $currentTime = time();
        $fin = $token->fin;
        if($currentTime>$fin)
            return $this->respond(['error'=>'Token caducado.'], 400);
            
        $model = new OrdenModel();
        $orden =  new \App\Entities\Orden();
        $orden->id_token = $token->id;
        $orden->fecha = (new DateTime())->format('Y-m-d');
        $orden->observaciones = isset($data['observaciones']) ? $data['observaciones']: null;
        
        $productos = $data['productos']; 
                
        $productoModel = new ProductoModel();
        $total = 0;
        $items = [];
        foreach($productos as $p){
            $producto = $productoModel->find($p['id']);
            if(!$producto)
                return $this->respond(['error'=>'Producto no valido.'], 400);
            $item = new \App\Entities\ItemOrden();
            $item->id_producto = $p['id'];
            $item->cantidad = $p['cantidad'];
            $items[] = $item;
            $total += $producto->precio*$item->cantidad;
        }
        $orden->total = $total;
        $id_orden = $model->guardar($orden);
        if($id_orden == 0 )
            return $this->respond(['error'=>'Token no valido.'], 400);
        $itemModel = new ItemOrdenModel();
        foreach($items as $i){
            $i->id_orden = $id_orden;
            $itemModel->save($i);
        }
        $orden->id = $id_orden;
        $orden->items = $items;

        $response['success'] = true;
        $response['orden']=$orden;
        
        return $this->respond($orden);
    }
    /*
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
    */
    
}
