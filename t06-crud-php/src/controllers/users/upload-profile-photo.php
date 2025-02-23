<?php
session_start();
require '/../../../database.php';
require_once __DIR__ . "/../../dao/user-dao.php";
require_once __DIR__ . "/../../../config.php";

if (!isset($_SESSION['user_id'], $_SESSION['user_name'])) {
    header("Location: " . BASE_URL . "view/login.php");
    exit;
}
$user_id = $_SESSION['user_id'];

$userDao = new UserDao();

$userPhoto = $userDao->getUserProfilePhotoById($user_id);

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['photo'])) {
    $uploadDir = '../../../uploads/';

    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0777, true);
    }

    $fileType = strtolower(pathinfo($_FILES['photo']['name'], PATHINFO_EXTENSION));

    $allowedTypes = ['jpg', 'jpeg', 'png', 'gif'];
    if (!in_array($fileType, $allowedTypes)) {
        $_SESSION['error'] = "Apenas arquivos JPG, JPEG, PNG e GIF são permitidos.";
        header("Location: " . BASE_URL . "view/profile.php");
        exit;
    }

    $fileName = uniqid() . '_' . basename($_FILES['photo']['name']);
    $targetFile = $uploadDir . $fileName;

    if (move_uploaded_file($_FILES['photo']['tmp_name'], $targetFile)) {
        $photoUrl = $fileName;

        $userDao->updateProfileImage($user_id, $photoUrl);

        $_SESSION['success'] = "Foto de perfil atualizada com sucesso!";
        header("Location: " . BASE_URL . "view/profile.php");

        exit;
    } else {
        $_SESSION['error'] = "Erro ao fazer upload da imagem.";
        header("Location: " . BASE_URL . "view/profile.php");
        exit;
    }

}

?>