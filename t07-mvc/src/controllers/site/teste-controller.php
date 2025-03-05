<?php

namespace src\controllers\site;
use src\controllers\BaseController;
include_once __DIR__ . '/../base-controller.php';
class TesteController extends BaseController{
    public function show(){

        $this->staticView('test', ['name' => 'testeeeeee']);
    }

    public function store(){
        $name = $this->input('name');
        $email = $this->input('email');
        $password = $this->input('password');

        echo $name;
        echo $email;
        echo $password;
    }

    public function showGroup(){

        $this->view('test', ['name' => 'testeeeeee']);
    }

    public function storeGroup(){
        $name = $this->input('name');
        $email = $this->input('email');
        $password = $this->input('password');

        echo $name;
        echo $email;
        echo $password;


    }
}