<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $pageTitle ?? 'Conex' ?></title>
    <link rel="stylesheet" href="/../public/css/main.css">
    <link rel="stylesheet" href="/../public/css/flash.css"> 
</head>

<body>
    <?php include __DIR__ . '/../components/flash.php'; ?>
    
    <aside class="side-bar">
        <img src="/../public/img/logo.svg" alt="logo" class="logo">
        <div class="side-bar-links">
            <a href="/">
                <img src="/../public/img/home.svg" class="icon">
                Página principal
            </a>
            <button id="searchButton" onclick="toggleSearch()">
                <img src="/../public/img/search.svg" class="icon">
                Pesquisar
            </button>
            <a href="/profile/<?php echo $loggedInUserID; ?>">
                <img src="/../public/img/profile.svg" class="icon">
                Perfil
            </a>
            <?php if ($isAuthenticated ?? false): ?>
            <div class="user-info">
                <span>Olá, <?= htmlspecialchars($username ?? 'Usuário') ?>!</span>
                <a href="/auth/logout">Sair</a>
            </div>
            <?php else: ?>
                <div class="auth-links">
                    <a href="/auth/login">Entrar</a>
                    <a href="/auth/signup">Cadastrar</a>
                </div>
            <?php endif; ?>
        </div>
    </aside>

    <div id="searchBox" class="search-box">
        <form method="GET" onsubmit="handleSearch(event)">
            <input id="searchInput" type="text" name="query" placeholder="Pesquisar" required>
            <button type="submit">Ir</button>
        </form>
        <div id="searchResults" class="search-results"></div>
    </div>

    <div class="main-content">
        {{content}}
    </div>
    <script>
        function redirectToProfile() {
            window.location.href = "/profile/<?php echo $loggedInUserID ?? ''; ?>";
        }
    </script>
    <script src="/js/search.js"></script>
</body>

</html>