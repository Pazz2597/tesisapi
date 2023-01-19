<?php

namespace App\Controllers;

use App\Models\ProductoModel;

class Home extends BaseController
{
    public function index()
    {
        $model = new ProductoModel();
        $productos = $model->findAll();
        //$template = $this->twig->load('productos/index.html.twig');
        //return $this->twig->render('productos/show.html', ['productos'=> $productos]);
         echo view('index.html');
    }
    public function productos()
    {
        $model = new ProductoModel();
        $entradas = $model->where('tipo', 'ent')->findAll();
        $fuertes = $model->where('tipo', 'fr')->findAll();
        $bebidas = $model->where('tipo', 'dr')->findAll();
        $licores = $model->where('tipo', 'lc')->orWhere('tipo', 'ck')->findAll();
        $promociones = $model->where('tipo', 'pr')->findAll();
        return $this->twig->render('productos/show.html', 
        [
            'entradas'=> $entradas,
            'fuertes'=> $fuertes,
            'bebidas'=> $bebidas,
            'licores'=>$licores,
            'promociones'=>$promociones
        ]);
    }
}
