<?php
session_start();

if (!isset($_SESSION['username'])) {
    header('Location: login.php');
    exit();
}

$username = htmlspecialchars($_SESSION['username']);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Perfil - <?php echo $username; ?></title>
    <link rel="stylesheet" href="../public/css/profile.css">
</head>

<body>
    <aside class="side-bar">
    <?php include("components/side-bar.php"); ?>
    </aside>

    <main class="profile-container">
        <section class="info-section">
            <div class="photo-container">
                <img src="../public/img/profile-photo.svg" alt="user" class="profile-photo">
                <button class="btn-edit">Editar Perfil</button>
                <a href="../src/logout-user.php" class="btn-logout" onclick="return confirm('Tem certeza que deseja sair?')">
                    Sair
                </a>
            </div>
            <div class="user-info">
                <h1 class="user-name"><?php echo $username; ?></h1>
                <div class="stats-container">
                    <span class="following"> 99 seguindo</span>
                    <span class="followers"> 99 seguidores</span>
                </div>
            </div>
        </section>
        <section class="feed-section">
            <form action="upload_photo.php" method="POST" enctype="multipart/form-data" class="add-post">
                <label for="photo">
                    <img src="../public/img/add-photo.svg" class="icon"> <br>
                    Adicionar foto
                </label>
                <input type="file" id="photo" name="photo" accept="image/*" style="display: none;">
                <button type="submit" class="btn-upload">Enviar</button>
            </form>
        </section>
    </main>
</body>

</html>