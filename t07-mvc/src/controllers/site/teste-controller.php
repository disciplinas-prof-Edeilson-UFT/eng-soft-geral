<?php

namespace src\controllers\site;
use src\controllers\BaseController;

include_once __DIR__ . '/../base-controller.php';
class TesteController extends BaseController{
    public function show($id, $user){
        $this->view('test', ['id' => $id, 'user' => $user]);
    }

    public function store(){
        $name = $this->input('name');
        $email = $this->input('email');
        $password = $this->input('password');

        echo $name;
        echo $email;
        echo $password;
    }

    public function showGroup($id){
        $this->view('test', [
            'name' => 'testeeeeee', 
            'id' => $id
        ]);
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