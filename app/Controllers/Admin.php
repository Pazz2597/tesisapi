<?php

namespace App\Controllers;

use App\Models\ItemOrdenModel;
use App\Models\OrdenModel;
use App\Models\UsuarioModel;
use CodeIgniter\HTTP\Request;
use DateInterval;
use DateTime;

class Admin extends BaseController
{
    public function login(){
        $model = new UsuarioModel();
        if($this->request->getMethod()=='post'){
            $username = $this->request->getPost('username');
            $password = $this->request->getPost('password');

            $mensajes = [];
            if($username == null && $password == null){
                $mensajes[] = 'El usuario y contraseña son requeridos';
                return $this->twig->render('usuarios/login.html.twig', ['mensajes'=>$mensajes]);
            }
            $usuario = $model->where('username', $username)->first();
            $mensajes[] = 'Usuario o contraseña incorrectos';
            if(!$usuario){
                return $this->twig->render('usuarios/login.html.twig', ['mensajes'=>$mensajes]);
            }

            $hash = $usuario->password;
            
            if(!password_verify((string)$password, $hash)){
                return $this->twig->render('usuarios/login.html.twig', ['mensajes'=>$mensajes]);
            }
            
            $session = session();
            $session->set(['user'=>$username]);
            return redirect('Admin::productos');
        }
        return $this->twig->render('usuarios/login.html.twig', ['mensajes'=>[]]);      
    }
    
    public function logout(){
        $session = session();
        $session->destroy();
        return redirect('Admin::login');
    }

    public function productos()
    {
        $session = session();
        $user = $session->get('user');
        if($user){
            return $this->twig->render('productos/index.html', ['user'=>$user]);
        }else{
            return redirect('Admin::login');
        }
    }

    public function pedidos(){
        $session = session();
        $user = $session->get('user');
        if($user){
            //$model = new OrdenModel();
            $db      = \Config\Database::connect();
            $fecha = (new DateTime())->format('Y-m-d');
            
            $sql = "SELECT o.*,  m.id as mesa_id, m.codigo
                        FROM orden o 
                        INNER JOIN token t ON o.id_token = t.id 
                        INNER JOIN mesa m ON t.id_mesa = m.id
                        WHERE (o.estado = :estado: OR o.estado = :estado1: OR o.estado = :estado2: ) AND o.fecha >= :fecha:";

            $pendientes = $db->query($sql, ['fecha'=>$fecha, 'estado'=> 'P', 'estado1'=> 'X', 'estado2'=> 'O'])->getResult();
            $setTime = function($p) {
                
                $fechaOrden = new DateTime($p->fecha);
                $timestampOrden = mktime(
                                            $fechaOrden->format("H"),
                                            $fechaOrden->format("i"),
                                            $fechaOrden->format("s"),
                                            $fechaOrden->format("n"),
                                            $fechaOrden->format("j"),
                                            $fechaOrden->format("Y")
                                        );
                $current = time();
                $p->tiempo = $current - $timestampOrden;
                return $p;
            };
            $pendientes = array_map( $setTime, $pendientes);      

            $atendidos = $db->query($sql, ['fecha'=>$fecha, 'estado'=> 'A', 'estado1'=> 'A', 'estado2'=> 'A'])->getResult();
            $cancelados = $db->query($sql, ['fecha'=>$fecha, 'estado'=> 'C', 'estado1'=> 'C', 'estado2'=> 'C'])->getResult();
            
            
            if($pendientes && count($pendientes)>0){
                
                foreach($pendientes as $p){
                    $builder = $db->table('orden_item');
                    $builder->select('orden_item.*, producto.nombre, producto.precio');
                    $builder->join('producto', 'orden_item.id_producto = producto.id', 'inner');
                    $items = $builder->where('id_orden', $p->id)->get()->getResult();
                    //$items = $itemModel->where('id_orden', $p->id)->findAll();
                    $p->items = $items;                    
                }
                foreach($atendidos as $p){
                    $builder = $db->table('orden_item');
                    $builder->select('orden_item.*, producto.nombre, producto.precio');
                    $builder->join('producto', 'orden_item.id_producto = producto.id', 'inner');
                    $items = $builder->where('id_orden', $p->id)->get()->getResult();
                    //$items = $itemModel->where('id_orden', $p->id)->findAll();
                    $p->items = $items;
                }
                foreach($cancelados as $p){
                    $builder = $db->table('orden_item');
                    $builder->select('orden_item.*, producto.nombre, producto.precio');
                    $builder->join('producto', 'orden_item.id_producto = producto.id', 'inner');
                    $items = $builder->where('id_orden', $p->id)->get()->getResult();
                    //$items = $itemModel->where('id_orden', $p->id)->findAll();
                    $p->items = $items;
                }
            }
            
            return $this->twig->render('compra/pedidos.html', ['user'=>$user, 
                'pendientes'=>$pendientes,
                'atendidos'=>$atendidos,
                'cancelados'=>$cancelados
            ]);
        }else{
            return redirect('Admin::login');
        }
    }

    public function procesar(int $id){
        $session = session();
        $user = $session->get('user');
        if($user){
            $model = new OrdenModel();
            $orden = $model->find($id);
            if($orden){
                $orden->estado = 'X';
                $model->update($id, $orden);
            }
            return redirect('admin/pedidos');
        }
        return redirect('Admin::login');        
    }
    public function despachar(int $id){
        $session = session();
        $user = $session->get('user');
        if($user){
            $model = new OrdenModel();
            $orden = $model->find($id);
            if($orden){
                $orden->estado = 'O';
                $model->update($id, $orden);
            }
            return redirect('admin/pedidos');
        }
        return redirect('Admin::login');        
    }
    public function cancelar(int $id){
        $session = session();
        $user = $session->get('user');
        if($user){
            $model = new OrdenModel();
            $orden = $model->find($id);
            if($orden){
                $orden->estado = 'C';
                $model->update($id, $orden);
            }
            return redirect('admin/pedidos');
        }
        return redirect('Admin::login');        
    }
}