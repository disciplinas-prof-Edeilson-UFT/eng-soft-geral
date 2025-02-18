function toggleSearch() {
    const searchBox = document.getElementById("searchBox");

    if (searchBox.classList.contains("open")) {
        searchBox.classList.remove("open");
        setTimeout(() => {
            searchBox.style.display = "none";
        }, 300); // Tempo igual à duração da transição
    } else {
        searchBox.style.display = "block";
        setTimeout(() => {
            searchBox.classList.add("open");
        }, 10); // Pequeno delay para garantir que o display: block seja aplicado
    }
}