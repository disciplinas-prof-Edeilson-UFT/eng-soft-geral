<?php
namespace core\mvc\helpers; 

use Routes;
use Exception;  
use core\http\Request;
class Parameters
{
    /*
        Recebe a URI e retorna um array com os parâmetros da rota atual (se houver) 
    */

    public static function getUri(){
        return parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
    }

    public static function getParams(string $router)
    {
        $routes = Routes::getRoutes();
        $method = Request::getMethod();

        if (!isset($routes[$method]) || !in_array($router, $routes[$method])) {
            throw new Exception("Rota {$router} não encontrada para o metodo {$method} ");
        }

        $routerPath = array_search($router, $routes[$method]);
        $params = self::filterRoute($routerPath);

        return array_values($params);
    }

    private static function filterRoute(string $router)
    {
        $requiredUri = self::getUri();
        $partesUri = explode('/', $requiredUri);
        $routeParts = explode('/', $router);

        $params = [];
        
        foreach ($routeParts as $index => $segmento) {
            if ($segmento !== $partesUri[$index]) {
                $params[] = $partesUri[$index];
            }
        }
        return $params;

    }
}