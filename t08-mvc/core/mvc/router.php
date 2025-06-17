<?php
namespace core;
use Routes;
use Exception;
use core\http\Request;
use core\http\Response;
use core\mvc\Controller;

class Router{
    private $routes;
    
    public function __construct()
    {
        $this->routes = Routes::getRouter();
    }

    public function dispatch()
    {
        $method = Request::getMethod();
        $uri = $this->getCleanUri();
        $result = $this->processAllRoutes($this->routes, $method, $uri);
        
        if ($result === null) {
            Response::errorView(404);
            return;
        }
        
        try {
            if (isset($result['middlewares'])) {
                $this->applyMiddlewares($result['middlewares']);
            }
            
            Controller::execute($result['controller']);
        } catch (Exception $e) {
            Response::errorView(500);
        }
    }

    private function processAllRoutes($routes, $method, $uri)
    {
        if (!isset($routes['groups'])) {
            return null;
        }
        
        foreach ($routes['groups'] as $prefix => $groupRoutes) {
            // Calcula o caminho do prefixo
            if ($prefix === '') {
                $prefixPath = '';
                $routeWithoutPrefix = $uri;
            } else {
                $prefixPath = '/' . trim($prefix, '/');
                if (strpos($uri, $prefixPath) !== 0) {
                    continue;
                }
                $routeWithoutPrefix = substr($uri, strlen($prefixPath));
            }
            
            // Verifica se existe a rota
            if (isset($groupRoutes[$method][$routeWithoutPrefix])) {
                return [
                    'controller' => $groupRoutes[$method][$routeWithoutPrefix],
                    'middlewares' => $groupRoutes['middleware'] ?? []
                ];
            }
            
            // Verifica rotas com parâmetros
            if (isset($groupRoutes[$method])) {
                foreach ($groupRoutes[$method] as $route => $controller) {
                    if ($this->matchRoute($route, $routeWithoutPrefix)) {
                        return [
                            'controller' => $controller,
                            'middlewares' => $groupRoutes['middleware'] ?? []
                        ];
                    }
                }
            }
        }
        return null;
    }

    private function applyMiddlewares($middlewares){
        foreach ($middlewares as $middleware) {
            switch ($middleware) {
                case 'auth':
                    $this->checkAuth();
                    break;
                default:
                    break;
            }
        }
    }

    private function checkAuth(){
        session_start();
        if (!isset($_SESSION['user_id']) || empty($_SESSION['user_id'])) {
            header('Location: /auth/login');
            exit;
        }
    }

    private function matchRoute($routePattern, $uri)
    {
        $routeParts = explode('/', trim($routePattern, '/'));
        $uriParts = explode('/', trim($uri, '/'));
        
        if (count($routeParts) !== count($uriParts)) {
            return false;
        }
        
        foreach ($routeParts as $index => $segment) {
            if (preg_match('/^\{([a-zA-Z0-9_]+)\}$/', $segment)) {
                continue;
            }
            
            if ($segment !== $uriParts[$index]) {
                return false;
            }
        }
        
        return true;
    }

    private function getCleanUri(){
        $uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        return rtrim($uri, '/') ?: '/';
    }
}