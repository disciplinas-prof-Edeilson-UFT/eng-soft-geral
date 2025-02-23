function toggleSearch() {
    const searchBox = document.getElementById("searchBox");

    if (searchBox.classList.contains("open")) {
        searchBox.classList.remove("open");
        setTimeout(() => {
            searchBox.style.display = "none";
        }, 300); 
    } else {
        searchBox.style.display = "block";
        setTimeout(() => {
            searchBox.classList.add("open");
        }, 10); 
    }
}

async function handleSearch(event) {
    event.preventDefault();

    const query = document.getElementById('searchInput').value;
    const resultsContainer = document.getElementById('searchResults');
    resultsContainer.innerHTML = 'Carregando...';

    try {
        const response = await fetch(`../src/controllers/pesquisa.php?query=${encodeURIComponent(query)}`);
        const results = await response.text();

        resultsContainer.innerHTML = results;
    } catch (error) {
        resultsContainer.innerHTML = '<p class="no-results">Erro ao buscar resultados.</p>';
        console.error('Erro na busca:', error);
    }
}
