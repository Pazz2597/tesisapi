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
        $productos = $model->findAll();
        return $this->twig->render('productos/show.html', ['productos'=> $productos]);
    }
}
