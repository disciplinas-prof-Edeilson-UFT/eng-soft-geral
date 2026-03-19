<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tela de Login</title>
    <link rel="stylesheet" href="/../public/css/login.css">
    <link rel="stylesheet" href="/../public/css/flash.css">
</head>

<body>
    <?php include __DIR__ . '/components/flash.php'; ?>
    <div class="container">
        <!-- Seção esquerda com logo -->
        <div class="left">
            <img src="../public/img/logo.svg" alt="Logo">
        </div>
        <!-- Seção direita com formulário -->
        <div class="right">
            <form method="POST" action="/auth/login">
                <div class="form-control">
                    <label for="email">Email</label>
                    <input type="email" id="email" name="email" required>
                </div>
                <div class="form-control">
                    <label for="password">Senha</label>
                    <input type="password" id="password" name="password" required>
                </div>
                <button type="submit" class="btn">Fazer Login</button>
                <div class="login-link">
                    Não tem uma conta? <a href="/auth/signup">Cadastre-se</a>
                </div>
            </form>
        </div>
    </div>
</body>

</html>