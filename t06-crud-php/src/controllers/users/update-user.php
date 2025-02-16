<?php

require_once __DIR__ . '/../../../database.php';
require_once __DIR__ . "/../../dao/user-dao.php";

session_start();
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name  = trim($_POST['name']);
    $email = trim($_POST['email']);
    $phone = trim($_POST['phone']);
    $bio   = trim($_POST['bio']);

    if (isset($_GET["id"])) {
        $id = $_GET["id"];
        try {
            $database = Database::getInstance();
            $userDao = new UserDao($database);
            $this->userDao->updateUser($name, $email, $bio, $phone, $id);
            header("Location: perfil.php?id=$id");
            exit;
        } catch(Exception $e) { 
            echo 'Erro: ' . $e->getMessage();
        }
    } else {
        echo "ID inválido";
    }
} else {
    header('Location: index.php');
    exit;
}