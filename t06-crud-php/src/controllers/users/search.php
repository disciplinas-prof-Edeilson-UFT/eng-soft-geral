<?php
require_once __DIR__ . '../../../dao/search-dao.php';

if (isset($_GET['query'])) {
    $query = $_GET['query'];

    $userRepo = new UserRepository();
    $usuarios = $userRepo->buscarUsuarios($query);

    if (count($usuarios) > 0) {
        foreach ($usuarios as $usuario) {
            $nome = htmlspecialchars($usuario['name']);
            $id = htmlspecialchars($usuario['id']);
            $foto = !empty($usuario['profile_pic'])
                ? "/eng-soft-geral/t06-crud-php/src/uploads/" . htmlspecialchars($usuario['profile_pic'])
                : 'https://via.placeholder.com/150'; // Imagem padrão

            echo "<div class='search-result'>";
            echo "<img src='$foto' alt='Foto de $nome' class='profile-pic'>";
            echo "<a href='/eng-soft-geral/t06-crud-php/view/profile.php?id=$id' class='user-link'>$nome</a>";
            echo "</div>";
        }
    } else {
        echo "<p class='no-results'>Nenhum usuário encontrado.</p>";
    }
}
