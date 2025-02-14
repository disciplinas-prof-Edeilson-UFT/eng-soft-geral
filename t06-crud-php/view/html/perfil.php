<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="/view/css/perfil.css">
</head>

<body>
    <aside class="side-bar">
        <img src="/img/logo.svg" alt="logo" class="logo">
        <div class="side-bar-links">
            <a href="home">
                <img src="/img/home.svg" class="icon">
                Página principal
            </a>
            <a href="search">
                <img src="/img/search.svg" class="icon">
                Pesquisar
            </a>
            <a href="perfil.html">
                <img src="/img/profile.svg" class="icon">
                Perfil
            </a>
        </div>
    </aside>
    <main class="profile-container">
        <section class="info-section">
            <div class="photo-container">
                <img src="/img/profile-photo.svg" alt="user" class="profile-photo">
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
        <form action="../../repositories/upload_photo.php" method="POST" enctype="multipart/form-data" class="add-post">
            <a href="perfil" class="add-post">
                <img src="/img/add-photo.svg" class="icon"> <br>
                adicionar post
            </a>
        </form>
    </main>
</body>

</html>