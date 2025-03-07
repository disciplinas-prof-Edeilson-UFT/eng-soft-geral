<?php
namespace src\controllers;

use core\http\Request;
use core\mvc\View;

class BaseController{
    public function view(string $view, $data = []){
        echo View::render($view, $data ?? []);
    }

    public function staticView(string $view, $data = []){
        echo View::renderOnlyView($view, $data ?? []);
    }

    public function redirect(string $path){
        header("Location: {$path}");
    }

    public function input(string $input){
        return Request::input($input);
    }

    public function query(string $query){
        return Request::query($query);
    }

    public function method(){
        return Request::getMethod();
    }



}