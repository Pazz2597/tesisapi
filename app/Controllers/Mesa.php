<?php
namespace App\Controllers;
use App\Entities\Token;
use App\Models\TokenModel;
use CodeIgniter\RESTful\ResourceController;
use CodeIgniter\API\ResponseTrait;
use App\Models\MesaModel;
use App\Models\OrdenModel;

class Mesa extends ResourceController
{
    use ResponseTrait;
    public function list(){
        $model = new MesaModel();
        $data['mesas'] = $model->select('id, codigo, descripcion, alerta')->findAll();
        return $this->respond($data);
    }
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
        $token->id_mesa = $userData->mesa_id;
        $model->save($token);
        return $this->respond($token);
    }
    public function alerta()
    {
        helper('jwt');
        helper('websocket');
        $model = new MesaModel();
        $userData = getUserFromRequest($this->request);
        
        $id = $userData->mesa_id;
        $mesa = $model->find($id);
        
        $mesa->alerta = $mesa->alerta ? "0":"1";
        $model->update($id, $mesa);
        $msg = [];
        $msg['mesa_id'] = $id;
        $msg['alerta'] = $mesa->alerta;
        $jsonMsg = json_encode($msg);
        send($jsonMsg);

        return $this->respond($mesa);
        
    }

    public function atendido()
    {
        helper('jwt');
        $model = new MesaModel();
        $userData = getUserFromRequest($this->request);
        
        $id = $userData->mesa_id;
        $mesa = $model->find($id);
        
        $mesa->atend = $mesa->atend ? 0:1;
        $model->update($id, $mesa);
        return $this->respond($mesa);
        
    }
    
    public function recibir(int $tokenNumber)
    {
        helper('jwt');
        helper('websocket');
        $tokenModel =  new TokenModel();

        $token = $tokenModel->where('token', $tokenNumber)->orderBy('inicio', 'DESC')->first();
        $userData = getUserFromRequest($this->request);
        $model = new MesaModel();
        $id = $userData->mesa_id;
        $mesa = $model->find($id);

        if($token && $token->id_mesa == $id){
            $ordeModel = new OrdenModel();
            $orden = $ordeModel->where('id_token', $token->id)->orderBy('fecha', 'DESC')->first();
            if($orden){
                $orden->estado = 'A';
                $ordeModel->update($orden->id, $orden);

                $msg = [];
                $msg['mesa_id'] = $id;
                $msg['alerta'] = $mesa->alerta;
                $jsonMsg = json_encode($msg);
                send($jsonMsg, 'R');
                
                return $this->respond([], 200);
            } 
        }

        return $this->respond([], 400);
        
    }
}
