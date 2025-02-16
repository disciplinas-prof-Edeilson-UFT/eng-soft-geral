<style>
    .side-bar {
        width: 25%;
        display: flex;
        flex-direction: column;
        border-right: 2px solid rgb(235, 233, 233);
    }

    .side-bar a {
        text-decoration: none;
        color: black;
        padding: 10px;
        font-size: 16px;
    }

    .side-bar-links {
        display: flex;
        flex-direction: column;
        padding-left: 5%;
        gap: 10px;
        margin-top: 20px;
        width: 80%;
    }

    .side-bar-links a:hover {
        background-color: #f2f2f2;
        border-radius: 5px;
        width: 100%;
        text-decoration: none;
    }

    .logo {
        width: 70%;
        margin-top: 30px;
    }

</style>

<img src="/img/logo.svg" alt="logo" class="logo">
<div class="side-bar-links">
    <a href="home">
        <img src="/img/home.svg" class="icon">
        Página principal
    </a>
    <a href="search">
        <img src="/img/search.svg" class="icon">
        Pesquisar
    </a>
    <a href="perfil.php">
        <img src="/img/profile.svg" class="icon">
        Perfil
    </a>
</div>

