<?php
session_start();

// Inclui arquivos necessários
require_once __DIR__ . "/../../../configs.php";
require_once __DIR__ . "/../../dao/user-dao.php";
require_once __DIR__ . "/../../../database.php";
require_once __DIR__ . '/../../../config.php';

// Inicializa a conexão com o banco de dados e o DAO
$database = Database::getInstance();
$dao = new UserDao($database);

// Verifica se o parâmetro 'id' foi passado na URL
if (!isset($_GET["id"])) {
    die("Parâmetro user_id não informado.");
}

$id = $_GET["id"];
$user = $dao->getUserById($id);

if (!$user) {
    die("Usuário não encontrado.");
}

// Processa o formulário de edição quando enviado
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['edit'])) {
        // Obtém e sanitiza os dados do formulário
        $name = trim($_POST['name']);
        $email = trim($_POST['email']);
        $phone = trim($_POST['phone']);
        $bio = trim($_POST['bio']);

        // Processa o upload da foto de perfil, se houver
        if (isset($_FILES['profile_pic']) && $_FILES['profile_pic']['error'] === UPLOAD_ERR_OK) {
            $uploadDir = __DIR__ . "/../../../src/uploads/";

            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0777, true);
            }

            $fileName = uniqid() . '_' . basename($_FILES['profile_pic']['name']);
            $uploadFile = $uploadDir . $fileName;

            $imageFileType = strtolower(pathinfo($uploadFile, PATHINFO_EXTENSION));
            $allowedTypes = ['jpg', 'jpeg', 'png', 'gif'];

            if (!in_array($imageFileType, $allowedTypes)) {
                die("Apenas arquivos JPG, JPEG, PNG e GIF são permitidos.");
            }

            if (move_uploaded_file($_FILES['profile_pic']['tmp_name'], $uploadFile)) {
                $profilePicPath = $fileName;
                $dao->updateProfilePic($profilePicPath, $id);
            } else {
                die("Erro ao fazer upload da imagem.");
            }
        }

        try {
            $dao->updateUser($name, $email, $bio, $phone, $id);
            $_SESSION['user_name'] = $name;
            header("Location: " . BASE_URL . "view/profile.php?id=$id");
            exit;
        } catch (Exception $e) {
            echo 'Erro: ' . $e->getMessage();
        }
    }

    if (isset($_POST['delete'])) {
        try {
            if ($dao->deleteUser($id)) {
                session_destroy();
                header("Location: " . BASE_URL . "view/home.php");
                exit;
            } else {
                echo "Erro ao deletar usuário.";
            }
        } catch (Exception $e) {
            echo 'Erro ao deletar: ' . $e->getMessage();
        }
    }
}

// Inclui a view de atualização de perfil
include __DIR__ . "/../../../view/profile-update.php";