<?php
session_start();

require_once __DIR__ . '/../../../database.php';
require_once __DIR__ . "/../../dao/user-dao.php";
require_once __DIR__ . '/../../../config.php';

if (isset($_GET['id'])) {
    $id = $_GET["id"];
    try {
        $database = Database::getInstance();
        $userDao = new UserDao($database);
        $userDao->deleteUser($id);
        session_destroy();

        header("Location: " . BASE_URL . "view/login.php");
        exit;
    } catch(Exception $e) { 
        echo 'Erro: ' . $e->getMessage();
    }
} else {
    echo "ID inválido";
}