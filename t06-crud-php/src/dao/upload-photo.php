<?php
session_start();
require '../../database.php'; // Arquivo com a conexão PDO

if (!isset($_SESSION['user_id'], $_SESSION['user_name'])) {
    header('Location: login.php');
    exit;
}

$pdo = Database::getInstance()->getConnection();
$user_id = $_SESSION['user_id'];

// Verifica se o usuário já tem uma foto salva
$stmt = $pdo->prepare("SELECT photo_url FROM user_photos WHERE user_id = :user_id LIMIT 1");
$stmt->execute(['user_id' => $user_id]);
$userPhoto = $stmt->fetch(PDO::FETCH_ASSOC);

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['photo'])) {
    $uploadDir = '../uploads/';
    $fileName = uniqid() . '_' . basename($_FILES['photo']['name']);
    $targetFile = $uploadDir . $fileName;
    
    if (move_uploaded_file($_FILES['photo']['tmp_name'], $targetFile)) {
        $photoUrl = $fileName;

        if ($userPhoto) {
            // Atualiza a foto existente
            $stmt = $pdo->prepare("UPDATE user_photos SET photo_url = :photo_url WHERE user_id = :user_id");
        } else {
            // Insere nova foto
            $stmt = $pdo->prepare("INSERT INTO user_photos (user_id, photo_url) VALUES (:user_id, :photo_url)");
        }
        
        $stmt->execute(['user_id' => $user_id, 'photo_url' => $photoUrl]);
        
        header('Location: /eng-soft-geral/t06-crud-php/view/profile.php');
        exit;
    } else {
        $error = "Erro ao enviar a imagem.";
    }
}
?>