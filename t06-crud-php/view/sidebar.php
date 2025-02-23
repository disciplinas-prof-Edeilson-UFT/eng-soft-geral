<aside class="side-bar">
    <img src="../public/img/logo.svg" alt="logo" class="logo">
    <div class="side-bar-links">
        <a href="home">
            <img src="../public/img/home.svg" class="icon">
            Página principal
        </a>
        <button id="searchButton" onclick="toggleSearch()">
            <img src="../public/img/search.svg" class="icon">
            Pesquisar
        </button>
        <div id="searchBox" class="search-box">
            <label for="searchInput" class="search-label">Pesquisa</label>
            <form action="../src/controllers/pesquisa.php" method="GET" onsubmit="handleSearch(event)">
                <input id="searchInput" type="text" name="query" placeholder="Pesquisar" required>
                <button type="submit">Ir</button>
            </form>
            <div id="searchResults" class="search-results"></div> 
        </div>

        <a href="perfil.html">
            <img src="../public/img/profile.svg" class="icon">
            Perfil
        </a>
    </div>
</aside>