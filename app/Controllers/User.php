<?php
namespace App\Controllers;
use App\Controllers\BaseController;

class User extends BaseController
{
    public function index($user_id = null)
    {
        // We load the CI welcome page with some lines of Javascript
        $this->twig->render('welcome_message.php', array('user_id' => $user_id));
    }
}