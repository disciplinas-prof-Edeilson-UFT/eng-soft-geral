<?php

require __DIR__ . '/../../dao/posts-dao.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: " . BASE_URL . "view/login.php");
    exit;
}

try {
    $postsDao = new PostsDao();
    $posts = $postsDao->getAllPosts();
} catch (PDOException $e) {
    die("Erro ao buscar dados: " . $e->getMessage());
}
