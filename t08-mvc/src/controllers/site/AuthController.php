<?php

namespace src\controllers\site;

use src\controllers\BaseController;
use src\services\AuthService;
use src\database\dao\UserDAO;
use Conex\MiniFramework\utils\Flash;

class AuthController extends BaseController{    
    private AuthService $authService;

    public function __construct() {
        $userDAO = new UserDAO();
        $this->authService = new AuthService($userDAO); 
    }

    public function showLogin(){
        $this->staticView('login');
    }

    public function login() {
        error_log("Login method called");
        
        $email = $this->input('email');
        $password = $this->input('password');
        
        error_log("Email: $email");
        
        try {
            $user = $this->authService->login($email, $password);
            error_log("User found: " . ($user ? 'YES' : 'NO'));

            if ($user) {
                $this->setSession('user_id', $user->getId());
                $this->setSession('username', $user->getUsername());
                
                Flash::success('Login realizado com sucesso!');
                error_log("Flash success set");
                
                $this->redirect('/');
                exit;
            }
            
            Flash::error('Email ou senha incorretos');
            error_log("Flash error set");
            
            $this->redirect('/auth/login');
            
        } catch (\Exception $e) {
            error_log("Exception: " . $e->getMessage());
            Flash::error('Erro no login: ' . $e->getMessage());
            $this->redirect('/auth/login');
        }
        exit;
    }

    public function showSignup(){
        $this->staticView('signup');
    }

    public function signup() {
        try {
            $username = $this->input('username');
            $email = $this->input('email');
            $password = $this->input('password');
            $confirm_password = $this->input('confirm_password');
            $phone = $this->input('phone');

            $this->authService->register($username, $email, $password, $confirm_password, $phone);

            Flash::success('Cadastro realizado com sucesso!');
            $this->redirect('/auth/login');
            
        } catch (\InvalidArgumentException $e) {
            Flash::error($e->getMessage());
            $this->redirect('/auth/signup');
        } catch (\Exception $e) {
            Flash::error('Erro interno do servidor');
            $this->redirect('/auth/signup');
        }
        exit;
    }
}