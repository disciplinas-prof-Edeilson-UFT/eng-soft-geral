<?php
declare(strict_types=1);

namespace src\services;

use src\database\dao\UserDAO;
use src\database\domain\User;
use src\database\mappers\UserMapper;

/**
 * Service responsável por regras de autenticação e registro de usuários
 */
class AuthService
{
    private UserDAO $userDAO;
    private UserMapper $userMapper;

    public function __construct(UserDAO $userDAO){
        $this->userDAO = $userDAO;
        $this->userMapper = new UserMapper();
    }

    public function signup(string $username, string $email, string $password, string $confirmPassword, string $phone, ?string $bio = null, ?string $profilePicUrl = null): User{
        $email = trim($email);
        $username = trim($username);

        if ($username === '' || mb_strlen($username) < 3) {
            throw new \InvalidArgumentException('Username deve ter pelo menos 3 caracteres');
        }
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new \InvalidArgumentException('Email inválido');
        }
        if ($password !== $confirmPassword) {
            throw new \InvalidArgumentException('Senhas não conferem');
        }
        if ($this->userDAO->checkEmailExists($email)) {
            throw new \InvalidArgumentException("Email já está em uso");
        }

        $user = new User($username, $email, null, $phone, $bio, $profilePicUrl);
        $user->setPassword($password, $confirmPassword);

        if (!$this->userDAO->insertUser($user)) {
            throw new \InvalidArgumentException("Erro ao criar usuário");
        }
        return $user;
    }

    /**
     * Realiza autenticação retornando o domain User ou null se email inexistente
     * Lança exceção se senha incorreta
     */
    public function login(string $email, string $password): ?User
    {
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