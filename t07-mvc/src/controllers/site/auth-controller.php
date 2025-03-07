<?php

namespace src\controllers\site;

use core\mvc\IModelRepository;
use src\controllers\BaseController;
use src\database\dao\IUserDAO;
use src\services\AuthService;
use src\database\domain\User;
use src\database\dao\UserDAO;
use core\mvc\ModelRepository;

require_once __DIR__ . '/../../database/domain/user.php';
require_once __DIR__ . '/../../database/dao/user-dao.php';
require_once __DIR__ . '/../../database/domain/user.php';
require_once __DIR__ . '/../../../core/mvc/model-repository.php';
require_once __DIR__ . '/../../services/auth-service.php';

class AuthController extends BaseController{    
    public $authService;
    public IUserDAO $userDAO;
    public User $user;
    public IModelRepository $IModelRespot;

    public function __construct() {
        $this->IModelRespot = new ModelRepository();
        $this->user = new User(null, null, null, null, null, null, null);
        $this->userDAO = new UserDAO($this->IModelRespot);
        $this->authService = new AuthService($this->userDAO, $this->user);
    }

    public function showLogin(){
        $this->staticView('login');
    }

    public function login(){
        $email = $this->input('email');
        $password = $this->input('password');

        $user = $this->authService->login($email, $password);

        if($user){
            $_SESSION['user'] = $user;
            header('Location: /feed?success=Loggedin');
        }else{
            return $this->staticView('login', ['errors' => ['Email ou senha inválidos']]);
        }
    }

    public function showSignup(){
        
        $this->staticView('signup');
    }

    public function signup(){
        try{
            $username = $this->input('username');
            $email = $this->input('email');
            $password = $this->input('password');
            $confirm_password = $this->input('confirm_password');
            $phone = $this->input('phone');
            $bio = null;
            $profile_pic_url = null;

            $this->authService->register($username, $email, $password, $confirm_password,$phone, $bio, $profile_pic_url);

            header('Location: /auth/login?success=');
        }catch(\InvalidArgumentException $e){
            return $this->staticView('signup', ['errors' => [$e->getMessage()]]);
        }catch(\Exception $e){
            return $this->staticView('signup', ['errors' => ["Error ao processar cadastro, tente novamente mais tarde", $e->getMessage()]]);
        }
        

    }
}