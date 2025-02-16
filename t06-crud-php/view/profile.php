<?php
session_start();
$userName = isset($_SESSION['user_name']) ? htmlspecialchars($_SESSION['user_name']) : header('Location: login.php');
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