<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $pageTitle ?? 'Conex' ?></title>
    <link rel="stylesheet" href="/public/css/main.css">
    <link rel="stylesheet" href="/public/css/flash.css"> 
    <?php if (isset($pageCSS)): ?>
        <link rel="stylesheet" href="/public/css/<?= $pageCSS ?>.css">
    <?php endif; ?>
</head>

<body>
    <?php include __DIR__ . '/../components/flash.php'; ?>
    
    <aside class="side-bar">
        <img src="/public/img/logo.svg" alt="logo" class="logo">
        <div class="side-bar-links">
            <a href="/">
                <img src="/public/img/home.svg" class="icon">
                Página principal
            </a>
            <button id="searchButton" onclick="toggleSearch()">
                <img src="/public/img/search.svg" class="icon">
                Pesquisar
            </button>

            <?php if ($isAuthenticated ?? false): ?>
                <a href="/profile/<?= $loggedInUserID; ?>">
                    <img src="/public/img/profile.svg" class="icon">
                    Perfil
                </a>
                <div class="user-info-side-bar">
                    <span>Olá, <?= htmlspecialchars($username ?? 'Usuário') ?>!</span>
                    <a href="/auth/logout">Sair</a>
                </div>
            <?php else: ?>
                <a href="/auth/login">
                    <img src="/public/img/profile.svg" class="icon">
                    Entrar
                </a>
            <?php endif; ?>
        </div>
    </aside>

    <div id="searchBox" class="search-box">
        <form onsubmit="handleSearch(event)">
            <input id="searchInput" type="text" name="query" placeholder="Pesquisar usuários..." required>
            <button type="submit">Buscar</button>
        </form>
        <div id="searchResults" class="search-results"></div>
    </div>

    <div class="main-content">
        {{content}}
    </div>

    <script src="/public/js/search.js"></script>
</body>
</html>