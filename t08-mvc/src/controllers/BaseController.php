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
        if (headers_sent($file, $line)) {
            error_log("Headers already sent in $file:$line - Cannot redirect to $path");
            exit;
        }
        
        header("Location: {$path}");
        exit;
    }

    public function input(string $input, $default = null){
        $value = Request::input($input);
        return $value !== null ? $value : $default;
    }

    public function query(string $query, $default = null){
        $value = Request::query($query);
        return $value !== null ? $value : $default;
    }

    public function file(string $key): ?array
    {
        return $_FILES[$key] ?? null;
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
        $userID = Session::get('user_id');
        $username = Session::get('username');
        $isAuth = Session::has('user_id');
        
        $data = [
            'loggedInUserID' => $userID,
            'username' => $username,
            'isAuthenticated' => $isAuth,
            'currentURL' => $_SERVER['REQUEST_URI'] ?? '/',
        ];
        return $data;
    }
}