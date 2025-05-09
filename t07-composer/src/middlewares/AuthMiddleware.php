<?php
namespace Conex\T07Composer\middlewares;

class AuthMiddleware {
    public function handle(callable $next) {
        if (!isset($_SESSION['user_id'])) {
            $_SESSION['error'] = "Você precisa estar logado para acessar esta página";
            header("Location: " . BASE_URL . "login");
            exit;
        }
        
        return $next();
    }
}
