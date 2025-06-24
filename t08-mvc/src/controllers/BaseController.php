<?php

namespace src\controllers;

use Conex\MiniFramework\utils\Session;
use Conex\MiniFramework\http\Request;
use Conex\MiniFramework\mvc\View;


class BaseController{

    public function __construct() {
        Session::start();
    }

    public function view(string $view, $data = []){
        $globalData = $this->getGlobalViewData();
        $viewData = array_merge($globalData, $data);
        
        echo View::render($view, $viewData);
    }

    public function staticView(string $view, $data = []){
        echo View::renderOnlyView($view, $data);
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

    public function getSession(string $key, $default = null){
        return Session::get($key, $default);
    }

    public function setSession(string $key, $value){
        return Session::set($key, $value);
    }

    public function removeSession(string $key){
        return Session::remove($key);
    }

    public function destroySession(){
        return Session::destroy();
    }

    private function getGlobalViewData(): array {
        return [
            'loggedInUserID' => Session::get('userID'),
            'username' => Session::get('username'),
            'isAuthenticated' => Session::has('userID'),
            'currentURL' => $_SERVER['REQUEST_URI'] ?? '/',
        ];
    }
}