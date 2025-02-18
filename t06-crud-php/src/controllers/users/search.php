<?php
require_once __DIR__ . '../../../dao/userRepository.php';

if (isset($_GET['query'])) {
    $query = $_GET['query'];

    $userRepo = new UserRepository();
    $usuarios = $userRepo->buscarUsuarios($query);

    if (count($usuarios) > 0) {
        echo "<ul>";
        foreach ($usuarios as $usuario) {
            echo "<li>" . htmlspecialchars($usuario['name']) . " - " . htmlspecialchars($usuario['email']) . "</li>";
        }
        echo "</ul>";
    } else {
        echo "Nenhum usuário encontrado.";
    }
}
