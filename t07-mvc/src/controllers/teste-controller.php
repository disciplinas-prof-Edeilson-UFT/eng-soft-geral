<?php

namespace src\controllers;
use src\controllers\BaseController;
include_once __DIR__ . '/base-controller.php';
class TesteController extends BaseController{
    public function show(){
        
        $this->view('test', ['name' => 'testeeeeee']);
    }

    public function store(){
        $name = $this->input('name');
        $email = $this->input('email');
        $password = $this->input('password');

        echo $name;
        echo $email;
        echo $password;
    }

}