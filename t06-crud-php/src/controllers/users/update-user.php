<?php
session_start();
require_once __DIR__ . "/../../dao/user-dao.php";
require_once __DIR__ . "/../../../database.php";

$database = Database::getInstance();
$dao = new UserDao($database);

if (!isset($_GET["id"])) {
    die("Parâmetro user_id não informado.");
}

$id = $_GET["id"];
$user = $dao->getUserById($id);

if (!$user) {
    die("Usuário não encontrado.");
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['edit'])) {
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $phone = trim($_POST['phone']);
    $bio = trim($_POST['bio']);

    try {
        $dao->updateUser($name, $email, $bio, $phone, $id);

        $_SESSION['user_name'] = $name;
        header("Location: /view/profile.php?id=$id");
        exit;
    } catch (Exception $e) {
        echo 'Erro: ' . $e->getMessage();
    }
}
include __DIR__ . "/../../../view/profile-update.php";