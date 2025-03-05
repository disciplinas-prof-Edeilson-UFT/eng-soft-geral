<?php
namespace src\controllers;

use core\http\Request;
use core\mvc\View;

class BaseController{
    protected function view(string $view, $data = []){
        echo View::render($view, $data ?? []);
    }

    protected function staticView(string $view, $data = []){
        echo View::renderOnlyView($view, $data ?? []);
    }

    protected function redirect(string $path){
        header("Location: {$path}");
    }

    protected function input(string $input){
        return Request::input($input);
    }

    protected function query(string $query){
        return Request::query($query);
    }

    protected function method(){
        return Request::getMethod();
    }



}