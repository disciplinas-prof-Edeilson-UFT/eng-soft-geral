<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="/../public/css/signup.css">
</head>

<body>
    <form method="POST" action="/auth/signup" class="form-group">

        <div class="icon">
            <img src="../public/img/logo.svg" alt="logo" class="logo">
        </div>
        <div class="form-wrapper">
            <div class="form-control">
                <label for="username">Nome: </label>
                <input type="text" name="username" id="username" placeholder="Nome Completo" />
            </div>
            <div class="form-control">
                <label for="phone">Telefone: </label>
                <input type="tel" name="phone" id="phone" placeholder="(xx) xxxxx-xxxx" />
            </div>
            <div class="form-control">
                <label for="email">Email: </label>
                <input type="email" name="email" id="email" placeholder="email@email.com" />
            </div>
            <div class="form-control">
                <label for="password">Senha: </label>
                <input type="password" name="password" id="password" placeholder="Senha" />
            </div>
            <div class="form-control">
                <label for="confirm_password">Confirme a senha: </label>
                <input type="password" name="confirm_password" id="confirm_password" placeholder="Confirme a Senha" />
            </div>
        </div>
        <div class="btn-wrapper">
            <button class="btn">continuar</button>
            <span class="login-link">já possui conta? <a href="/auth/login" class="link">Faça Login</a></span>
        </div>

    </form>
</body>

</html>