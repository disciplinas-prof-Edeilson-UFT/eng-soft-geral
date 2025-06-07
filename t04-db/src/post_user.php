<?php

require_once __DIR__ . '/../database.php';
require_once __DIR__ . '/users.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = trim($_POST['name']);
    $phone = trim($_POST['phone']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $password_confirm = $_POST['password-confirm'];

    if (empty($name) || empty($phone) || empty($email) || empty($password) || empty($password_confirm)) {        
        header("Location: /view/signup.php?error=todos-os-campos-obrigatorios");
        exit();
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        header("Location: /view/signup.php?error=email-invalido");
        exit();
    }

    if ($password !== $password_confirm) {
        header("Location: /view/signup.php?error=confirmação-de-senha-diferente");
        exit();
    }

    try{
        $database = Database::getInstance();
        $users = new Users($database);

        if ($users->checkEmailExists($email)) {
            header("Location: /view/signup.php?error=email-ja-registrado");
            exit();
        }

        $password_hash = password_hash($password, PASSWORD_ARGON2I);

        if ($users->createUser($name, $email, $password_hash, $phone)) {
            header("Location: /?success=usuario-cadastrado-com-sucesso");
            exit();
        } else {
            header("Location: /view/signup.php?error=erro-ao-cadastrar-usuario");
            exit();
        }
    }catch (Exception $e) {
        header("Location: /view/signup.php?error=" . urlencode($e->getMessage()));
        exit();
    }

}