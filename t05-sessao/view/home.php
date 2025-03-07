<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Página Principal</title>
    <link rel="stylesheet" href="../public/css/home.css">
</head>

<body>
    <!-- Barra Lateral -->
    <?php include __DIR__ . '/side-bar.php'; ?>

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
</body>

</html>