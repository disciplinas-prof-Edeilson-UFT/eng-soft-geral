<?php
session_start();
require '../database.php'; // Arquivo com a conexão PDO
$pdo = Database::getInstance()->getConnection();

if (!isset($_SESSION['user_id'], $_SESSION['user_name'])) {
    header('Location: login.php');
    exit;
}

$user_id = $_SESSION['user_id'];
$userName = htmlspecialchars($_SESSION['user_name']);

// Verifica se o usuário já tem uma foto salva
$stmt = $pdo->prepare("SELECT photo_url FROM user_photos WHERE user_id = :user_id LIMIT 1");
$stmt->execute(['user_id' => $user_id]);
$userPhoto = $stmt->fetch(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="../public/css/profile.css">
</head>

<body>
    <aside class="side-bar">
        <img src="../public/img/logo.svg" alt="logo" class="logo">
        <div class="side-bar-links">
            <a href="home">
                <img src="../public/img/home.svg" class="icon">
                Página principal
            </a>
            <a href="search">
                <img src="../public/img/search.svg" class="icon">
                Pesquisar
            </a>
            <a href="perfil.html">
                <img src="../public/img/profile.svg" class="icon">
                Perfil
            </a>
        </div>
    </aside>
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
            </div>
        </section>
        <section class="info-section">
            <!-- Foto de perfil do usuário -->
            <div class="profile-photo-container">

            </div>

            <!-- Imagem do feed -->
            <div class="feed-photo-container">
                <?php
                if (is_array($userPhoto) && isset($userPhoto['photo_url'])) {
                    $photoUrl = $userPhoto['photo_url']; 
                    $absolutePath = 'C:/xampp/htdocs/eng-soft-geral/t06-crud-php/src/uploads/' . $photoUrl;

                    $relativePath = '/eng-soft-geral/t06-crud-php/src/uploads/' . $photoUrl;

                    if (file_exists($absolutePath)) {
                        echo '<img src="' . htmlspecialchars($relativePath) . '" alt="Imagem do Feed" class="feed-image">';
                    } else {
                        echo '<p>Imagem não encontrada.</p>';
                    }
                } 
                ?>
            </div>



            <?php if (!$userPhoto): ?>
                <div class="upload-container">
                    <form action="../src/dao/upload-photo.php" method="POST" enctype="multipart/form-data" class="add-post">
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
</body>

</html>