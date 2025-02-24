<?php
function fetchPosts() {
    session_start();
    require __DIR__ . '/../../database.php'; 

    if (!isset($_SESSION['user_id'])) {
        die("Acesso negado.");
    }

    $user_id = $_SESSION['user_id'];

    try {
        $pdo = Database::getInstance()->getConnection();

        $stmt = $pdo->prepare("SELECT u.id, u.name, u.profile_pic, p.photo_url
                                FROM seguidores s
                                JOIN users u ON s.seguindo_id = u.id
                                LEFT JOIN user_photos p ON u.id = p.user_id
                                WHERE s.usuario_id = :user_id
                                ORDER BY p.upload_date DESC");
        $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    } catch (PDOException $e) {
        die("Erro ao buscar dados: " . $e->getMessage());
    }
}