<?php
session_start();

require_once __DIR__ . '/../../../database.php';
require_once __DIR__ . "/../../dao/user-dao.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    if (empty($email) || empty($password)) {
        $_SESSION['error'] = "Todos os campos são obrigatórios!";
        header("Location: ../../../view/login.php");
        exit();
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $_SESSION['error'] = "Formato de email inválido!";
        header("Location: ../../../view/login.php?error=credenciais-invalidas");
        exit();
    }

    $database = Database::getInstance();
    $users = new UserDao($database);

    $user = $users->getUserByEmail($email);

    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['user_name'] = $user['name'];
        $_SESSION['user_email'] = $user['email'];
        $_SESSION['LAST_ACTIVITY'] = time();
        $_SESSION['success'] = "Login realizado com sucesso!";
        
        header("Location: ../../../view/profile.php");
        exit();
    } else {
        $_SESSION['error'] = "Credenciais inválidas!";
        header("Location: ../../../view/login.php?error=credencial-errada");
        exit();
    }
}
