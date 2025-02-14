<?php
session_start();

require_once __DIR__ . '/database.php';
require_once __DIR__ . '/users.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = trim($_POST['name']);
    $phone = trim($_POST['phone']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $password_confirm = $_POST['password-confirm'];

    if (empty($name) || empty($phone) || empty($email) || empty($password) || empty($password_confirm)) {
        $_SESSION['error'] = "Todos os campos são obrigatórios!";
        header("Location: /eng-soft-geral/t05-sessao/view/html/cadastro.php");
        exit();
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $_SESSION['error'] = "Email inválido!";
        header("Location: /eng-soft-geral/t05-sessao/view/html/cadastro.phpp");
        exit();
    }

    if ($password !== $password_confirm) {
        $_SESSION['error'] = "As senhas não coincidem!";
        header("Location: /eng-soft-geral/t05-sessao/view/html/cadastro.php");
        exit();
    }

    if (strlen($password) < 8) {
        $_SESSION['error'] = "A senha deve ter pelo menos 8 caracteres!";
        header("Location: /eng-soft-geral/t05-sessao/view/html/cadastro.php");
        exit();
    }

    $database = Database::getInstance();
    $users = new Users($database);

    if ($users->checkEmailExists($email)) {
        $_SESSION['error'] = "Este email já está registrado!";
        header("Location: /eng-soft-geral/t05-sessao/view/html/cadastro.php");
        exit();
    }

    $password_hash = password_hash($password, PASSWORD_ARGON2I);

    if ($users->createUser($name, $email, $password_hash, $phone)) {
        $_SESSION['success'] = "Usuário cadastrado com sucesso!";
        header("Location: /eng-soft-geral/t05-sessao/view/html/login.php");
        exit();
    } else {
        $_SESSION['error'] = "Erro ao cadastrar o usuário!";
        header("Location: /eng-soft-geral/t05-sessao/view/html/cadastro.php");
        exit();
    }
}