<?php

namespace App\Controllers;

use App\Models\ItemOrdenModel;
use App\Models\OrdenModel;
use App\Models\UsuarioModel;
use CodeIgniter\HTTP\Request;
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
            $model = new OrdenModel();
            $db      = \Config\Database::connect();
            
            /*
            $db      = \Config\Database::connect();
            $builder = $db->table('orden');
            $builder->select('*');
            $builder->join('orden_item', 'orden.id = orden_item.id_orden', 'left');
            $pendientes = $builder->get()->getResult();
            var_dump($pendientes);
            */
            $fecha = (new DateTime())->format('Y-m-d');
            $pendientes = $model->where('estado', 'P')->where('fecha >=', $fecha) ->findAll();
            $atendidos = $model->where('estado', 'A')->where('fecha >=', $fecha) ->findAll();
            $cancelados = $model->where('estado', 'C')->where('fecha >=', $fecha) ->findAll();
            
            
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
}
