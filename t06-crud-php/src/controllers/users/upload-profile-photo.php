<?php
session_start();

require_once '/../../../database.php';
require_once __DIR__ . "/../../dao/UserDAO.php";
require_once __DIR__ . "/../../../dir-config.php";
require_once __DIR__ . "/../../utils/UploadHandler.php";

if (!isset($_SESSION['user_id'], $_SESSION['user_name'])) {
    header("Location: " . BASE_URL . "view/login.php");
    exit;
}
$user_id = $_SESSION['user_id'];

$userDAO = new UserDAO();

$userPhoto = $userDAO->getUserProfilePhotoById($user_id);

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['photo'])) {
    $uploadDir = '../../../uploads/profile/';
    $allowedTypes = ['jpg', 'jpeg', 'png', 'gif'];

    $dbUpdateCallback = function ($photoUrl) use ($user_id, $userDAO) {
        return $userDAO->updateProfileImage($user_id, $photoUrl);
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