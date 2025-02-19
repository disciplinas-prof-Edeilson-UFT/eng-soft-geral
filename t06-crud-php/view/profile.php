<?php
session_start();
require '../database.php';

if (!isset($_SESSION['user_id'], $_SESSION['user_name'])) {
    header('Location: login.php');
    exit;
}

// Cria a conexão com o banco de dados
$pdo = Database::getInstance()->getConnection();

// Inclui o arquivo com as funções
require '../src/controllers/seguir.php';

$user_id = isset($_GET['id']) ? $_GET['id'] : $_SESSION['user_id'];
$logged_in_user_id = $_SESSION['user_id'];

// Busca os dados do usuário no banco
$stmt = $pdo->prepare("SELECT id, name FROM users WHERE id = :id LIMIT 1");
$stmt->execute(['id' => $user_id]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$user) {
    echo "<p>Usuário não encontrado.</p>";
    exit;
}

$userName = htmlspecialchars($user['name']);

// Verifica se o usuário já tem uma foto salva
$stmt = $pdo->prepare("SELECT photo_url FROM user_photos WHERE user_id = :user_id LIMIT 1");
$stmt->execute(['user_id' => $user_id]);
$userPhoto = $stmt->fetch(PDO::FETCH_ASSOC);

// Verifica se o usuário já está seguindo
$estaSeguindo = verificarSeguimento($pdo, $logged_in_user_id, $user_id);

// Se o botão for pressionado, chama a função
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['seguir'])) {
    $usuario_id = $_POST['usuario_id'];
    $seguindo_id = $_POST['seguindo_id'];
    $acao = $_POST['acao'];

    if ($acao === 'seguir') {
        $mensagem = seguirUsuario($pdo, $usuario_id, $seguindo_id);
    } elseif ($acao === 'parar_de_seguir') {
        $mensagem = pararDeSeguir($pdo, $usuario_id, $seguindo_id);
    }

    if (empty($mensagem)) {
        header("Location: profile.php?id=$user_id"); // Redireciona para evitar reenvio
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile</title>
    <link rel="stylesheet" href="../public/css/profile.css">
</head>

<body>
    <?php include 'sidebar.php'; ?>

    <main class="profile-container">
        <section class="info-section">
            <div class="photo-container">
                <img src="../public/img/profile-photo.svg" alt="user" class="profile-photo">
                <button class="btn-edit">Editar Perfil</button>
            </div>
            <div class="user-info">
                <h1 class="user-name"><?php echo $userName; ?></h1>
                <div class="stats-container">
                    <span class="following"> 99 seguindo</span>
                    <span class="followers"> 99 seguidores</span>
                </div>
                <?php if ($user_id !== $logged_in_user_id): ?>
                    <form method="POST" action="">
                        <input type="hidden" name="usuario_id" value="<?php echo $logged_in_user_id; ?>">
                        <input type="hidden" name="seguindo_id" value="<?php echo $user_id; ?>">
                        <?php if ($estaSeguindo): ?>
                            <input type="hidden" name="acao" value="parar_de_seguir">
                            <button type="submit" name="seguir">Deixar de seguir</button>
                        <?php else: ?>
                            <input type="hidden" name="acao" value="seguir">
                            <button type="submit" name="seguir">Seguir</button>
                        <?php endif; ?>
                    </form>
                <?php endif; ?>
            </div>
        </section>
        <section class="info-section">
            <div class="feed-photo-container">
                <?php if (is_array($userPhoto) && isset($userPhoto['photo_url'])): ?>
                    <?php
                    $photoUrl = $userPhoto['photo_url'];
                    $absolutePath = 'C:/xampp/htdocs/eng-soft-geral/t06-crud-php/src/uploads/' . $photoUrl;
                    $relativePath = '/eng-soft-geral/t06-crud-php/src/uploads/' . $photoUrl;
                    ?>
                    <?php if (file_exists($absolutePath)): ?>
                        <img src="<?php echo htmlspecialchars($relativePath); ?>" alt="Imagem do Feed" class="feed-image">
                    <?php else: ?>
                        <p>Imagem não encontrada.</p>
                    <?php endif; ?>
                <?php endif; ?>
            </div>

            <?php if (!$userPhoto && $user_id == $_SESSION['user_id']): ?>
                <div class="upload-container">
                    <form action="../src/controllers/upload-photo-feed.php" method="POST" enctype="multipart/form-data" class="add-post">
                        <label for="photo">
                            <img src="../public/img/add-photo.svg" class="icon"> <br>
                            Adicionar foto
                        </label>
                        <input type="file" id="photo" name="photo" accept="image/*">
                        <button type="submit" class="btn-upload">Enviar</button>
                    </form>
                </div>
            <?php endif; ?>
        </section>
    </main>
    <script src="../public/js/search.js"></script>
</body>

</html>