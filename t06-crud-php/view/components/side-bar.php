<?php
$baseUrl = "http://localhost/eng-soft-geral/t06-crud-php/"; 
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="../public/css/sidebar.css">
</head>

<body>
    <aside class="side-bar">
        <img src="../public/img/logo.svg" alt="logo" class="logo">
        <div class="side-bar-links">
            <a href="/">
                <img src="../public/img/home.svg" class="icon">
                Página principal
            </a>
            <button id="searchButton" onclick="toggleSearch()">
                <img src="../public/img/search.svg" class="icon">
                Pesquisar
            </button>
            <a href="/view/profile.php" onclick="redirectToProfile()">
                <img src="../public/img/profile.svg" class="icon">
                Perfil
            </a>
        </div>
    </aside>
    <div id="searchBox" class="search-box">
        <form action="../src/controllers/pesquisa.php" method="GET" onsubmit="handleSearch(event)">
            <input id="searchInput" type="text" name="query" placeholder="Pesquisar" required>
            <button type="submit">Ir</button>
        </form>
        <div id="searchResults" class="search-results"></div>
    </div>
    <script>
        function redirectToProfile() {
            window.location.href = "<?= BASE_URL ?>view/profile.php?id=<?php echo isset($_SESSION['user_id']) ? $_SESSION['user_id'] : ''; ?>";
        }
    </script>
    <script src="../public/js/search.js"></script>
</body>

</html>