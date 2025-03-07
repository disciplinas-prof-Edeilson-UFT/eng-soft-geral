<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="../public/css/perfil.css">
</head>

<body>
    <!-- Barra Lateral -->
    <?php include __DIR__ . '/side-bar.php'; ?>

    <main class="profile-container">
        <section class="info-section">
            <div class="photo-container">
                <img src="../public/img/profile-photo.svg" alt="user" class="profile-photo">
                <button class="btn-edit">Editar Perfil</button>
            </div>
            <div class="user-info">
                <h1 class="user-name">Usuario123</h1>
                <div class="stats-container">
                    <span class="following"> 99 seguindo</span>
                    <span class="followers"> 99 seguidores</span>
                </div>
            </div>
        </section>
        <section class="feed-section">
            <a href="perfil" class="add-post">
                <img src="../public/img/add-photo.svg" class="icon"> <br>
                adicionar post
            </a>
        </section>
    </main>
</body>

</html>