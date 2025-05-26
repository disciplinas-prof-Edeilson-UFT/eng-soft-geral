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
        const response = await fetch(`${baseUrl}search?query=${encodeURIComponent(query)}`);
        const results = await response.json(); 
        
        let html = '';
        if (results.users && results.users.length > 0) {
            results.users.forEach(user => {
                html += `<div class='search-result'>`;
                html += `<img src='${user.photo}' alt='Foto de ${user.name}' class='profile-pic'>`;
                html += `<a href='${baseUrl}profile?id=${user.id}' class='user-link'>${user.name}</a>`;
                html += `</div>`;
            });
        } else {
            html = `<p class='no-results'>Nenhum usuário encontrado.</p>`;
        }
        
        resultsContainer.innerHTML = html;
    } catch (error) {
        resultsContainer.innerHTML = '<p class="no-results">Erro ao buscar resultados.</p>';
        console.error('Erro na busca:', error);
    }
}