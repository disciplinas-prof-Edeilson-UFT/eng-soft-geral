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