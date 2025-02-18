<?php
function seguirUsuario($pdo, $usuario_id, $seguindo_id) {
    if (verificarSeguimento($pdo, $usuario_id, $seguindo_id)) {
        return "Você já segue este usuário.";
    }
    
    $query = "INSERT INTO seguidores (usuario_id, seguindo_id) VALUES (?, ?)";
    $stmt = $pdo->prepare($query);
    return $stmt->execute([$usuario_id, $seguindo_id]) ? "" : "Erro ao seguir o usuário.";
}

function verificarSeguimento($pdo, $usuario_id, $seguindo_id) {
    $query = "SELECT COUNT(*) FROM seguidores WHERE usuario_id = ? AND seguindo_id = ?";
    $stmt = $pdo->prepare($query);
    $stmt->execute([$usuario_id, $seguindo_id]);
    return $stmt->fetchColumn() > 0;
}

function pararDeSeguir($pdo, $usuario_id, $seguindo_id) {
    if (!verificarSeguimento($pdo, $usuario_id, $seguindo_id)) {
        return "Você não segue este usuário.";
    }
    
    $query = "DELETE FROM seguidores WHERE usuario_id = ? AND seguindo_id = ?";
    $stmt = $pdo->prepare($query);
    return $stmt->execute([$usuario_id, $seguindo_id]) ? "" : "Erro ao parar de seguir o usuário.";
}