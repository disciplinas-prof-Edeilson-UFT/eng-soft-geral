<?php

require __DIR__ . '/../../dao/PostDAO.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: " . BASE_URL . "view/login.php");
    exit;
}

$user_id = $_SESSION['user_id'];

try {
    $postsDao = new PostsDao();
    $posts = $postsDao->getPostsById($user_id);
} catch (PDOException $e) {
    die("Erro ao buscar dados: " . $e->getMessage());
}
