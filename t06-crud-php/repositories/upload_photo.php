<?php
// upload_photo.php

// Inicia a sessão
session_start();

// Verifica se o usuário está logado
if (!isset($_SESSION['user_id'])) {
    die("Acesso negado. Por favor, faça login para continuar.");
}

// Inclui o arquivo de conexão com o banco de dados
require_once __DIR__ . '../../t05-sessao/database.php';

// Verifica se o formulário foi enviado
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Verifica se o arquivo foi enviado sem erros
    if (isset($_FILES['photo']) && $_FILES['photo']['error'] === UPLOAD_ERR_OK) {
        $file = $_FILES['photo'];

        // Validações
        $allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];
        $maxSize = 5 * 1024 * 1024; // 5 MB

        if (!in_array($file['type'], $allowedTypes)) {
            $_SESSION['error'] = "Tipo de arquivo não permitido. Apenas JPEG, PNG e GIF são aceitos.";
            header("Location: /eng-soft-geral/t05-sessao/dashboard.php");
            exit();
        }

        if ($file['size'] > $maxSize) {
            $_SESSION['error'] = "O arquivo é muito grande. O tamanho máximo permitido é 5 MB.";
            header("Location: /eng-soft-geral/t05-sessao/dashboard.php");
            exit();
        }

        // Define o diretório de upload
        $uploadDir = 'uploads/';
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }

        // Gera um nome único para o arquivo
        $fileName = uniqid() . '_' . basename($file['name']);
        $uploadFilePath = $uploadDir . $fileName;

        // Move o arquivo para o diretório de upload
        if (move_uploaded_file($file['tmp_name'], $uploadFilePath)) {
            // Obtém o ID do usuário da sessão
            $userId = $_SESSION['user_id'];

            // Insere os metadados da foto no banco de dados
            $database = Database::getInstance();
            $pdo = $database->getConnection();

            $stmt = $pdo->prepare("INSERT INTO user_photos (user_id, photo_url, upload_date) VALUES (:user_id, :photo_url, :upload_date)");
            $stmt->execute([
                ':user_id' => $userId,
                ':photo_url' => $uploadFilePath,
                ':upload_date' => date('Y-m-d H:i:s')
            ]);

            $_SESSION['success'] = "Foto enviada com sucesso!";
            header("Location: /eng-soft-geral/t05-sessao/dashboard.php");
            exit();
        } else {
            $_SESSION['error'] = "Erro ao mover o arquivo para o diretório de upload.";
            header("Location: /eng-soft-geral/t05-sessao/dashboard.php");
            exit();
        }
    } else {
        $_SESSION['error'] = "Erro no upload do arquivo.";
        header("Location: /eng-soft-geral/t05-sessao/dashboard.php");
        exit();
    }
} else {
    $_SESSION['error'] = "Método de requisição inválido.";
    header("Location: /eng-soft-geral/t05-sessao/dashboard.php");
    exit();
}
?>