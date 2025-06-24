<?php

namespace src\controllers\site;

use src\controllers\BaseController;
use src\services\AuthService;
use src\database\dao\UserDAO;

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
        $email= $this->input('email');
        $password= $this->input('password');

        try {
            $user= $this->authService->login($email, $password);

            if ($user) {
                $this->setSession('user_id', $user->getId());
                $this->setSession('username', $user->getUsername());
                
                //Flash::success('Login realizado com sucesso!');
                $this->redirect('/');
                exit;
            }
            
            //Flash::error('Email ou senha incorretos');
            $this->redirect('/auth/login');
            
        } catch (\Exception $e) {
            //Flash::error('Erro no login: ' . $e->getMessage());
            
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

            //Flash::success('Cadastro realizado com sucesso!');
            $this->redirect('/auth/login');
            
        } catch (\InvalidArgumentException $e) {
            //Flash::error($e->getMessage());
            $this->redirect('/auth/signup');
        } catch (\Exception $e) {
            //Flash::error('Erro interno do servidor');
            $this->redirect('/auth/signup');
        }
        exit;
    }
}