<?php
declare(strict_types=1);

namespace src\services;

use src\database\dao\UserDAO;
use src\database\domain\User;
use src\database\mappers\UserMapper;


class AuthService
{
    private UserDAO $userDAO;
    private UserMapper $userMapper;

    public function __construct(UserDAO $userDAO){
        $this->userDAO = $userDAO;
        $this->userMapper = new UserMapper();
    }

    public function signup(string $username, string $email, string $password, string $confirmPassword, string $phone, ?string $bio = null, ?string $profilePicUrl = null): User {
        $user = new User($username, $email, null, $phone, $bio, $profilePicUrl);
        $user->setPassword($password, $confirmPassword);

        if ($this->userDAO->checkEmailExists($user->getEmail())) {
            throw new \InvalidArgumentException('Email já está em uso');
        }
        if (!$this->userDAO->insertUser($user)) {
            throw new \RuntimeException('Erro ao criar usuário');
        }
        return $user;
    }

    public function login(string $email, string $password): ?User {
        $email = trim($email);
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