<?php

require_once __DIR__ . '/../../dao/search-dao.php';
require_once __DIR__ . '/../../../dir-config.php';

if (isset($_GET['query'])) {
    $query = $_GET['query'];

    $searchDao = new SearchDao();
    $users = $searchDao->SearchUsers($query);

    if (count($users) > 0) {
        foreach ($users as $user) {
            $nome = htmlspecialchars($user['username']);
            $id = htmlspecialchars($user['id']);
            $foto = !empty($user['profile_pic_url'])
                ? BASE_URL . "uploads/avatars/" . htmlspecialchars($user['profile_pic_url'])
                : BASE_URL . "public/img/profile.svg"; 

            echo "<div class='search-result'>";
            echo "<img src='$foto' alt='Foto de $nome' class='profile-pic'>";
            echo "<a href='" . BASE_URL . "view/profile.php?id=$id' class='user-link'>$nome</a>";
            echo "</div>";
        }
    } else {
        echo "<p class='no-results'>Nenhum usuário encontrado.</p>";
    }
}