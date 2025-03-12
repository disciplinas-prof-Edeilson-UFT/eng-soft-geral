<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Página Principal</title>
    <link rel="stylesheet" href="home.css">
</head>

<body>
    <!-- Barra Lateral -->
    <aside class="side-bar">
    <?php include("components/side-bar.php"); ?>
    </aside>

    <!-- Conteúdo Principal -->
    <main class="container">
        <!-- Seção de Posts -->
        <section class="feed">
            <article class="post">
                <header class="user-info">
                    <div class="avatar" aria-label="Foto do Usuário"></div>
                    <span class="username">Usuário1233</span>
                </header>
                <div class="image-placeholder" aria-label="Imagem do Post"></div>
            </article>
            <hr class="divider">
            <article class="post">
                <header class="user-info">
                    <div class="avatar" aria-label="Foto do Usuário"></div>
                    <span class="username">Usuário1233</span>
                </header>
                <div class="image-placeholder" aria-label="Imagem do Post"></div>
            </article>
        </section>

        
    </main>
    <!-- Barra Lateral Direita -->
    <aside class="sidebar">
            <!-- Perfil do Usuário -->
            <section class="user-profile">
                <header>
                    <div class="avatar" aria-label="Avatar do Usuário"></div>
                    <div class="profile-info">
                        <span class="username">Usuário1233</span>
                        <a href="#" class="change-link">Mudar</a>
                    </div>
                </header>
            </section>

            <!-- Sugestões -->
            <section class="suggestions">
                <h2>Sugestões para você</h2>
                <ul class="suggestion-list">
                    <li class="suggestion-item">
                        <div class="avatar" aria-label="Foto de Sugestão"></div>
                        <span class="username">Usuário1233</span>
                        <a href="#" class="follow-link">Seguir</a>
                    </li>
                    <!-- Mais sugestões aqui -->
                </ul>
            </section>
        </aside>
</body>

</html>
