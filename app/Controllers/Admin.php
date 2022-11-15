<?php

namespace App\Controllers;

class Admin extends BaseController
{
    public function index()
    {
        //$template = $this->twig->load('productos/index.html.twig');
        return $this->twig->render('productos/index.html', []);
        //return view('welcome_message');
    }
}
