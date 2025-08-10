<?php 
namespace src\services;

use src\database\dao\UserDAO;
use src\database\domain\User;
use src\database\mappers\UserMapper;

class AuthService {
    private UserDAO $userDAO;
    private UserMapper $userMapper;

    public function __construct(UserDAO $userDAO)
    {
        $this->userDAO = $userDAO;
        $this->userMapper = new UserMapper();
    }

    public function signup($username, $email, $password, $confirm_password, $phone, $bio = null, $profile_pic_url = null): User {
        if ($this->userDAO->checkEmailExists($email)) {
            throw new \InvalidArgumentException("Email já está em uso");
        }

        $user = new User($username, $email, null, $phone, $bio, $profile_pic_url);
        $user->setPassword($password, $confirm_password);
        
        if (!$this->userDAO->insertUser($user)){
            throw new \InvalidArgumentException("Erro ao criar usuário");
        }
        return $user;
    }
    
    public function login($email, $password): ?User {
        $userData = $this->userDAO->getUserAuthDataByEmail($email);
        
        if (!$userData) {
            return null; 
        }

        $user = $this->userMapper->mapToUserAuth($userData);
        
        if (!password_verify($password, $user->getPasswordHash())) {
            throw new \InvalidArgumentException('senha incorreta');
        }
        
        return $user;
    }

}