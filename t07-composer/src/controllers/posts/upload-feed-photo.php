<?php
session_start();

require_once __DIR__ . "/../../dao/posts-dao.php"; 
require_once __DIR__ . "/../../dao/user-dao.php";
require_once __DIR__ . "/../../../dir-config.php";
require_once __DIR__ . "/../../utils/upload-handler.php";

if (!isset($_SESSION['user_id'], $_SESSION['user_name'])) {
    header("Location: " . BASE_URL . "view/login.php");
    exit;
}
$user_id = $_SESSION['user_id'];

$postsDao = new postsDao(); 
$userDao = new UserDao();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['photo'])) {
    $uploadDir = '../../../uploads/feed/';
    $allowedTypes = ['jpg', 'jpeg', 'png', 'gif'];

    $dbUpdateCallback = function ($photoUrl) use ($user_id, $postsDao) {
        return $postsDao->createPost($user_id, $photoUrl);
    };

    $uploadResult = UploadHandler::handleUpload($_FILES['photo'], $uploadDir, $allowedTypes, $dbUpdateCallback);

    if ($uploadResult['success']) {
        $_SESSION['success'] = "Foto do feed atualizada com sucesso!";
    } else {
        $_SESSION['error'] = $uploadResult['error'];
    }
    header("Location: " . BASE_URL . "view/profile.php"); 
    exit;
}
?>