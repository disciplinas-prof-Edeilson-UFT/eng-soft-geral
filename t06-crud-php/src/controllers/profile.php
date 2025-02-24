<?php
// Inclui os arquivos necessários
require __DIR__ . '/../../database.php'; 
require __DIR__ . '/seguir.php';        

// Inicia a sessão se ainda não estiver ativa
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Verifica se o usuário está logado
if (!isset($_SESSION['user_id'], $_SESSION['user_name'])) {
    header('Location: login.php'); // Redireciona para a página de login
    exit;
}

$pdo = Database::getInstance()->getConnection();

// Define o ID do usuário a ser visualizado (ou o ID do usuário logado, se não especificado)
$user_id = isset($_GET['id']) ? intval($_GET['id']) : $_SESSION['user_id'];
$logged_in_user_id = $_SESSION['user_id'];

// Busca os dados do usuário no banco de dados
$stmt = $pdo->prepare("SELECT id, name, profile_pic FROM users WHERE id = :id LIMIT 1");
$stmt->execute(['id' => $user_id]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

// Verifica se o usuário existe
if (!$user) {
    die("<p>Usuário não encontrado.</p>");
}

// Busca a foto do feed do usuário
$stmt = $pdo->prepare("SELECT photo_url FROM user_photos WHERE user_id = :user_id LIMIT 1");
$stmt->execute(['user_id' => $user_id]);
$userPhoto = $stmt->fetch(PDO::FETCH_ASSOC)['photo_url'] ?? null;

// Verifica se o usuário logado já está seguindo o perfil visualizado
$estaSeguindo = verificarSeguimento($pdo, $logged_in_user_id, $user_id);

// Processa a ação de seguir ou parar de seguir
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

// Conta quantas pessoas o usuário está seguindo
$stmt = $pdo->prepare("SELECT COUNT(*) as seguindo FROM seguidores WHERE usuario_id = :user_id");
$stmt->execute(['user_id' => $user_id]);
$seguindo = $stmt->fetch(PDO::FETCH_ASSOC)['seguindo'] ?? 0;

// Conta quantas pessoas estão seguindo o usuário
$stmt = $pdo->prepare("SELECT COUNT(*) as seguidores FROM seguidores WHERE seguindo_id = :user_id");
$stmt->execute(['user_id' => $user_id]);
$seguidores = $stmt->fetch(PDO::FETCH_ASSOC)['seguidores'] ?? 0;

// Prepara os dados do usuário para a view
$userData = [
    'userName' => htmlspecialchars($user['name']), 
    'user_id' => $user_id,                         // ID do usuário visualizado
    'logged_in_user_id' => $logged_in_user_id,     // ID do usuário logado
    'profile_pic' => $user['profile_pic'],        // Foto de perfil do usuário
    'userPhoto' => $userPhoto,                    // Foto do feed do usuário
    'estaSeguindo' => $estaSeguindo,              // Status de seguimento
    'seguindo' => $seguindo,                      // Quantidade de pessoas que o usuário segue
    'seguidores' => $seguidores                   // Quantidade de seguidores do usuário
];

// Verifica se os dados do usuário foram carregados corretamente
if (!isset($userData) || !is_array($userData)) {
    die("Erro: Dados do usuário não foram carregados corretamente.");
}

// Define o nome do usuário e redireciona se não estiver logado
$userName = isset($userData['userName']) ? htmlspecialchars($userData['userName']) : header('Location: ' . BASE_URL . 'view/login.php');
$userId = isset($_SESSION['user_id']) ? htmlspecialchars($_SESSION['user_id']) : header('Location: ' . BASE_URL . 'view/login.php');

// Define a foto de perfil (ou usa uma padrão se não houver)
$profilePhoto = !empty($userData['profile_pic']) ? BASE_URL . "src/uploads/" . htmlspecialchars($userData['profile_pic']) : BASE_URL . "public/img/default-avatar.png";
?>