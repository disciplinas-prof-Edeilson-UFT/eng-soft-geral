<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="/css/main.css">
</head>

<body>
    <aside class="side-bar">
        <img src="/img/logo.svg" alt="logo" class="logo">
        <div class="side-bar-links">
            <a href="/">
                <img src="/img/home.svg" class="icon">
                Página principal
            </a>
            <button id="searchButton" onclick="toggleSearch()">
                <img src="/img/search.svg" class="icon">
                Pesquisar
            </button>
            <a href="/view/profile.php">
                <img src="/img/profile.svg" class="icon">
                Perfil
            </a>
        </div>
    </aside>
    <div id="searchBox" class="search-box">
        <form action="../../src/controllers/pesquisa.php" method="GET" onsubmit="handleSearch(event)">
            <input id="searchInput" type="text" name="query" placeholder="Pesquisar" required>
            <button type="submit">Ir</button>
        </form>
        <div id="searchResults" class="search-results"></div>
    </div>

    <div class="main-content">
        {{content}}
    </div>

    <script src="/js/search.js"></script>
</body>

</html>