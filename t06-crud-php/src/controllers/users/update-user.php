<?php
session_start();

require_once __DIR__ . "/../../../dir-config.php";
require_once __DIR__ . "/../../dao/UserDAO.php";
require_once __DIR__ . "/../../../database.php";
require_once __DIR__ . "/../../utils/UploadHandler.php";

$userDAO = new UserDAO();

if (!isset($_GET["id"])) {
    die("Parâmetro user_id não informado.");
}

$id = $_GET["id"];

$user = $userDAO->getUserProfileById($id);

if (!$user) {
    die("Usuário não encontrado.");
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['edit'])) {
        $name = trim($_POST['username']);
        $email = trim($_POST['email']);
        $phone = trim($_POST['phone']);
        $bio = trim($_POST['bio']);

        if (isset($_FILES['profile_pic_url']) && $_FILES['profile_pic_url']['error'] === UPLOAD_ERR_OK) {
            $uploadDir = __DIR__ . "/../../../uploads/avatars/";
            $allowedTypes = ['jpg', 'jpeg', 'png', 'gif'];

            $dbUpdateCallback = function ($photoUrl) use ($id, $userDAO) {
                return $userDAO->updateProfilePic($photoUrl, $id);
            };

            $uploadResult = UploadHandler::handleUpload($_FILES['profile_pic_url'], $uploadDir, $allowedTypes, $dbUpdateCallback);

            if (!$uploadResult['success']) {
                die($uploadResult['error']);
            }
        }

        try {
            $userDAO->updateUser($name, $email, $bio, $phone, $id);
            $_SESSION['user_name'] = $name;
            header("Location: " . BASE_URL . "view/profile.php?id=$id");
            exit;
        } catch (Exception $e) {
            echo 'Erro: ' . $e->getMessage();
        }
    } else if(isset($_POST['delete'])) {
        try {
            $userDAO->deleteUser($id);
            session_destroy();
            header("Location: " . BASE_URL . "view/login.php");
            exit;
        } catch (Exception $e) {
            echo 'Erro: ' . $e->getMessage();
        }
    } else if (isset($_POST['logout'])) {
        session_destroy();
        header("Location: " . BASE_URL . "view/login.php");
        exit;
    }
}
