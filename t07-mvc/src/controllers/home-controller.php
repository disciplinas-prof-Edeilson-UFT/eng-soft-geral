<?php 
namespace src\controllers;

use core\mvc\View;

class HomeController{
    public function show(){
        $params = [
            'name' => 'testee',
        ];
       echo View::render('test', $params);
    }
}