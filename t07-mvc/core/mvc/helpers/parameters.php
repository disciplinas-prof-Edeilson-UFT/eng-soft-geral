<?php
namespace core\mvc\helpers; 

use Routes;
use Exception;  
use core\http\Request;
class Parameters
{
    /*
        recupera os parametros da rota como um array para serem passados para o controller   
    */

    public static function getUri(){
        return parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
    }

    //recebe uma URI e retorna seus parametros
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

    //localiza o router, aplica o filterRoutes e retorna os parametros ex: /users/{id} é /users/1 retorna ['1']
    public static function getParams(string $router)
    {
        $routes = Routes::getRouter();
        $method = Request::getMethod();

        if (!isset($routes[$method]) || !in_array($router, $routes[$method])) {
            throw new Exception("Rota {$router} não encontrada para o metodo {$method} ");
        }

        $routerPath = array_search($router, $routes[$method]);
        $params = self::filterRoute($routerPath);

        return array_values($params);
    }
}