<?php
require __DIR__ . '/../../database.php';
require __DIR__ . '/seguir.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}


if (!isset($_SESSION['user_id'], $_SESSION['user_name'])) {
    header('Location: login.php');
    exit;
}

$pdo = Database::getInstance()->getConnection();

$user_id = isset($_GET['id']) ? intval($_GET['id']) : $_SESSION['user_id'];
$logged_in_user_id = $_SESSION['user_id'];

// Busca os dados do usuário
$stmt = $pdo->prepare("SELECT id, name FROM users WHERE id = :id LIMIT 1");
$stmt->execute(['id' => $user_id]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$user) {
    die("<p>Usuário não encontrado.</p>");
}

// Busca a foto do usuário
$stmt = $pdo->prepare("SELECT photo_url FROM user_photos WHERE user_id = :user_id LIMIT 1");
$stmt->execute(['user_id' => $user_id]);
$userPhoto = $stmt->fetch(PDO::FETCH_ASSOC)['photo_url'] ?? null;

// Verifica se o usuário já está seguindo
$estaSeguindo = verificarSeguimento($pdo, $logged_in_user_id, $user_id);

// Processa seguir/parar de seguir
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['acao'])) {
    $acao = $_POST['acao'];

    if ($acao === 'seguir') {
        seguirUsuario($pdo, $logged_in_user_id, $user_id);
    } elseif ($acao === 'parar_de_seguir') {
        pararDeSeguir($pdo, $logged_in_user_id, $user_id);
    }

    header("Location: profile.php?id=$user_id");
    exit();
}

$userData = [
    'userName' => htmlspecialchars($user['name']),
    'user_id' => $user_id,
    'logged_in_user_id' => $logged_in_user_id,
    'userPhoto' => $userPhoto,
    'estaSeguindo' => $estaSeguindo
];

?>
