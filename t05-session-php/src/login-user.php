<?php
session_start();

require_once __DIR__ . '/../database.php';
require_once __DIR__ . '/users.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    if (empty($email) || empty($password)) {
        $_SESSION['error'] = "Todos os campos são obrigatórios!";
        header("Location: /view/login.php?error=todos-os-campos-obrigatorios");
        exit();
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $_SESSION['error'] = "Formato de email inválido!";
        header("Location: /view/login.php?error=formato-de-email-invalido");
        exit();
    }

    try{
        $database = Database::get_instance();
        $users = new Users($database);

        $user = $users->get_user_by_email($email);

        if ($user && password_verify($password, $user['password_hash'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['user_email'] = $user['email'];
            $_SESSION['LAST_ACTIVITY'] = time();
            $_SESSION['success'] = "Login realizado com sucesso!";
            
            header("Location: /?success=login-realizado-com-sucesso");
            exit();
        } else {
            $_SESSION['error'] = "Credenciais inválidas!";
            header("Location: /view/login.php?error=credencial-errada");
            exit();
        }
    }catch (Exception $e) {
        $_SESSION['error'] = "Erro ao conectar ao banco de dados: " . $e->getMessage();
        header("Location: /view/login.php?error=" . urlencode($e->getMessage()));
        exit();
    }
}