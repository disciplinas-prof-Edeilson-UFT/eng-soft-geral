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
        $uriParts = explode('/', $requiredUri);
        $routeParts = explode('/', $router); 

        //echo 'routeParts: ' . implode(', ', $routeParts) . '<br>';

        if(count($uriParts) !== count($routeParts)){
            throw new Exception("Rota {$router} não compatível com a URI: {$requiredUri}");
        }

        $params = [];

        
        foreach ($routeParts as $index => $segment) {
            if ($segment !== $uriParts[$index]) {
                if ($routeParts[0] === 'groups'){
                    $params['groups'][] = $uriParts[$index];
                }
                $params[] = $uriParts[$index];
            }

            //echo 'Índice: ' . $index . '<br>';
            //echo 'Segmento: ' . $segment . '<br>';
            //echo 'Segmento referente ao índice: ' . $uriParts[$index]  . '<br>';
            //echo 'Route Parts: ' . implode(', ', $routeParts) . '<br>';
            foreach ($params as $param) {
                echo 'Parâmetros: ' . $param . '<br>';
            }
        }

        //echo 'Parâmetros: ' . implode(', ', $params) . '<br>';
        return $params;

    }

    public static function matchRoutes(array $routeParts, array $uriParts)
    {
        $params = [];

        foreach($routeParts as $index => $segment){
            if ($segment !== $uriParts[$index]){
                $routeParts[0] == 'groups' ?? $params['groups'][] = $uriParts[$index];
            }
        }
    }

    //localiza o router, aplica o filterRoutes e retorna os parametros ex: /users/{id} é /users/1 retorna ['1']
    public static function getRouterParams(string $controllerMethodPath)
    {
        $routes = Routes::getRouter();
        $method = Request::getMethod(); 
        $params = [];

        if(isset($routes[$method])){
            foreach($routes[$method] as $route => $controller){
                if($controller === $controllerMethodPath){
                    $params = self::filterRoute($route);
                }
            }
        }

        if(isset($routes['groups'])){
            foreach($routes['groups'] as $groupPrefix => $group){
                    foreach($group[$method] as $route => $controller){
                        if ($controller === $controllerMethodPath){
                            $routeFormatted = '/' . trim($groupPrefix, '/') . '/' . trim($route, '/');

                            $params = self::filterRoute($routeFormatted);
                        }
                    } 
            }
        }
        /*foreach ($params as $param) {
            echo 'Parâmetros: ' . $param . '<br>';
        }*/

        if ($params = []) {
            throw new Exception("Rota não encontrada");
        }

        return array_values($params);
    }
}