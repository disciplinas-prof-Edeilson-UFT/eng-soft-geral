<?php
namespace Conex\T07Composer\controllers\site;

use Conex\T07Composer\Database;
use Conex\T07Composer\dao\UserDAO;

class AuthController {
    private $userDAO;
    
    public function __construct() {
        $database = Database::getInstance();
        $this->userDAO = new UserDAO($database);
    }

    public function showSignupForm() {
        require_once __DIR__ . '/../../../view/signup.php';
    }

    public function signup(array $data) {
        $name = trim($data['name']);
        $phone = trim($data['phone']);
        $email = trim($data['email']);
        $password = $data['password'];
        $password_confirm = $data['password-confirm'];

        if (empty($name) || empty($phone) || empty($email) || empty($password) || empty($password_confirm)) {
            $_SESSION['error'] = "Todos os campos são obrigatórios!";
            header("Location: " . BASE_URL . "signup?error=todos-os-campos-obrigatorios");
            exit();
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $_SESSION['error'] = "Email inválido!";
            header("Location: " . BASE_URL . "signup?error=email-invalido");
            exit();
        }

        if ($password !== $password_confirm) {
            $_SESSION['error'] = "As senhas não coincidem!";
            
            header("Location: " . BASE_URL . "signup?error=confirmação-de-senha-diferente");
            exit();
        }

        if (strlen($password) < 8) {
            $_SESSION['error'] = "A senha deve ter pelo menos 8 caracteres!";
            header("Location: " . BASE_URL . "signup?error=confirmação-de-senha-diferente");

            exit();
        }

        $database = Database::getInstance();
        $users = new UserDAO($database);

        if ($users->checkEmailExists($email)) {
            $_SESSION['error'] = "Este email já está registrado!";
            header("Location: " . BASE_URL . "signup?error=email-ja-registrado");
            exit();
        }

        $password_hash = password_hash($password, PASSWORD_ARGON2I);

        if ($users->createUser($name, $email, $password_hash, $phone)) {
            $_SESSION['success'] = "Usuário cadastrado com sucesso!";
            header("Location: " . BASE_URL . "login?success=usuario-cadastrado-com-sucesso");
            exit();
        } else {
            $_SESSION['error'] = "Erro ao cadastrar o usuário!";
            header("Location: " . BASE_URL . "signup?error=erro-ao-cadastrar-usuario");
            exit();
        }
    }
    
    public function showLoginForm() {
        require_once __DIR__ . '/../../../view/login.php';
    }
    
    public function login(array $data) {
        $email = trim($data['email']);
        $password = trim($data['password']);

        if (empty($email) || empty($password)) {
            $_SESSION['error'] = "Todos os campos são obrigatórios!";
            header("Location: " . BASE_URL . "login");
            exit();
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $_SESSION['error'] = "Formato de email inválido!";
            header("Location: " . BASE_URL . "login");
            exit();
        }

        $user = $this->userDAO->getUserAuthDataByEmail($email);

        if ($user && password_verify($password, $user['password_hash'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_name'] = $user['username'];
            $_SESSION['user_email'] = $user['email'];
            $_SESSION['LAST_ACTIVITY'] = time();
            $_SESSION['success'] = "Login realizado com sucesso!";
            header("Location: " . BASE_URL . "feed?success=login-realizado-com-sucesso");
            exit();
        } else {
            $_SESSION['error'] = "Credenciais inválidas!";
            header("Location: " . BASE_URL . "login");
            exit();
        }
    }
    
    public function logout() {
        session_unset();
        session_destroy();
        header("Location: " . BASE_URL . "login");
        exit();
    }
}