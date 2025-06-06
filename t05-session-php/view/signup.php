<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="../public/css/signup.css">
</head>

<body>
    <form class="form-group" method="POST" action="../src/post-user.php"> 

        <div class="icon">
            <img src="../public/img/logo.svg" alt="logo" class="logo">
        </div>
        <div class="form-wrapper">
            <div class="form-control">
                <label for="name">Nome: </label>
                <input type="text" name="name" id="name" required />
            </div>
            <div class="form-control">
                <label for="phone">Telefone: </label>
                <input type="tel" name="phone" id="phone" required />
            </div>
            <div class="form-control">
                <label for="email">Email: </label>
                <input type="email" name="email" id="email" required />
            </div>
            <div class="form-control">
                <label for="password">Senha: </label>
                <input type="password" name="password" id="password" required />
            </div>
            <div class="form-control">
                <label for="password-confirm">Confirme a senha: </label>
                <input type="password" name="password-confirm" id="password-confirm" required />
            </div>
        </div>
        <div class="btn-wrapper">
            <button class="btn">continuar</button>
            <span class="login-link">já possui conta? <a href="/view/login.php" class="link">Faça Login</a></span>
        </div>

    </form>
</body>

</html>