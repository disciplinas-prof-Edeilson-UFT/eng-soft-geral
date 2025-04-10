<?php
session_start();

require '/../../../database.php';
require_once __DIR__ . "/../../dao/user-dao.php";
require_once __DIR__ . "/../../../dir-config.php";
require_once __DIR__ . "/../../utils/upload-handler.php";

if (!isset($_SESSION['user_id'], $_SESSION['user_name'])) {
    header("Location: " . BASE_URL . "view/login.php");
    exit;
}
$user_id = $_SESSION['user_id'];

$userDao = new UserDao();

$userPhoto = $userDao->getUserProfilePhotoById($user_id);

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['photo'])) {
    $uploadDir = '../../../uploads/profile/';
    $allowedTypes = ['jpg', 'jpeg', 'png', 'gif'];

    $dbUpdateCallback = function ($photoUrl) use ($user_id, $userDao) {
        return $userDao->updateProfileImage($user_id, $photoUrl);
    };

    $uploadResult = UploadHandler::handleUpload($_FILES['photo'], $uploadDir, $allowedTypes, $dbUpdateCallback);

    if ($uploadResult['success']) {
        $_SESSION['success'] = "Foto de perfil atualizada com sucesso!";
    } else {
        $_SESSION['error'] = $uploadResult['error'];
    }

    header("Location: " . BASE_URL . "view/profile.php");
    exit;
}
?>