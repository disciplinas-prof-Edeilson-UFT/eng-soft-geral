<?php

namespace src\controllers;

use Conex\MiniFramework\utils\Session;
use Conex\MiniFramework\http\Request;
use Conex\MiniFramework\mvc\View;


class BaseController{

    public function __construct() {
        error_log("BaseController constructor called");
        Session::start();
        
        // ✅ Debug da sessão após start
        error_log("Session after start: " . print_r($_SESSION, true));
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
        $userID = Session::get('user_id');
        $username = Session::get('username');
        $isAuth = Session::has('user_id');
        
        // ✅ Debug das sessões
        error_log("Session debug - user_id: " . ($userID ?? 'NULL'));
        error_log("Session debug - username: " . ($username ?? 'NULL'));
        error_log("Session debug - has user_id: " . ($isAuth ? 'TRUE' : 'FALSE'));
        
        $data = [
            'loggedInUserID' => $userID,
            'username' => $username,
            'isAuthenticated' => $isAuth,
            'currentURL' => $_SERVER['REQUEST_URI'] ?? '/',
        ];
        
        // ✅ Debug do array retornado
        error_log("Global view data: " . print_r($data, true));
        
        return $data;
    }
}