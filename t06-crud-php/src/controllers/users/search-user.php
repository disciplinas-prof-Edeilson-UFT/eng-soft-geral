<?php

require_once __DIR__ . '/../../dao/search-dao.php';
require_once __DIR__ . '/../../../config.php';

if (isset($_GET['query'])) {
    $query = $_GET['query'];

    $userRepo = new SearchDao();
    $usuarios = $userRepo->buscarUsuarios($query);

    if (count($usuarios) > 0) {
        foreach ($usuarios as $usuario) {
            $nome = htmlspecialchars($usuario['name']);
            $id = htmlspecialchars($usuario['id']);
            $foto = !empty($usuario['profile_pic'])
                ? BASE_URL . "src/uploads/" . htmlspecialchars($usuario['profile_pic'])
                : 'https://via.placeholder.com/150'; 

            echo "<div class='search-result'>";
            echo "<img src='$foto' alt='Foto de $nome' class='profile-pic'>";
            echo "<a href='" . BASE_URL . "view/profile.php?id=$id' class='user-link'>$nome</a>";
            echo "</div>";
        }
    } else {
        echo "<p class='no-results'>Nenhum usuário encontrado.</p>";
    }
}