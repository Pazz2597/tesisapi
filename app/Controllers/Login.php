<?php
namespace App\Controllers;
use CodeIgniter\RESTful\ResourceController;
use CodeIgniter\API\ResponseTrait;
use App\Models\MesaModel;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class Login extends ResourceController
{
    use ResponseTrait;

    public function index($codigo = '', $secreto = '')
    {
        try {
            helper('encryption');  
            $mesaModel = new MesaModel();
            
            $mesa = $mesaModel->where('codigo', $codigo)->first();
            
            if(is_null($mesa)) {
                return $this->respond(['error' => 'Datos de la mesa incorrectos.'], 401);
            }
            
            $hash = $mesa->secreto;
            $pwd_verify = password_verify($secreto, $hash);

    
            if(!$pwd_verify) {
                return $this->respond(['error' => 'Datos de la mesa incorrectos.'], 401);
            }
            
            $key = getSecret(); 
            $iat = time(); 
            $exp = $iat + 2592000;

            $payload = array(
                "iss" => "RESTAURANTE",
                "aud" => "RESTAURANTE",
                "sub" => "Authentication",
                "iat" => $iat, 
                "exp" => $exp, 
                "mesa_id" => $mesa->id,
            );
                        
            $token = JWT::encode($payload, $key, 'HS256');
            
    
            $response = [
                'token' => $token,
                'expires_at'=> $exp
            ];
            
            return $this->respond($response, 200);
        } catch (\Exception $e) {
            return $this->respond($e->getMessage());
        }        
    }

}
