<?php
namespace core\mvc;

use Exception;
use core\mvc\helpers\Parameters;
use core\http\Response;
class Controller
{
    /* O Controller executa o método do controller correspondente à rota acessada pelo usuário. Para isso, o Controller deve ser capaz de identificar qual é o controller e o método que deve
    ser executado
    
    */

    //Executa o metodo do controller correspondente à rota acessada pelo usuário, passando os parâmetros
    public static function execute(string $router)
    {
        self::validateRouteFormat($router);
        
        //Sepra controller e method
        list($controller, $method) = explode('@', $router);
        
        //Concatena o nome completo da classe
        $className = "src\\controllers\\" . $controller;
        
        self::classExists($className);

        $controllerInstance = new $className();
    
        self::methodExists($controllerInstance, $method, $className);
        
        //recupera parametros e executa
        $params = Parameters::getParams($router);
        call_user_func_array([$controllerInstance, $method], $params);
    }

    private static function validateRouteFormat(string $router)
    {
        if (!str_contains($router, '@')) {
            throw new Exception("Formato de rota inválido!  correto: Controller@method");
        }
    }

    private static function classExists(string $classe)
    {
        if (!class_exists($classe)) {
            throw new Exception("Controller {$classe} não encontrado");
            //Response::errorView(404);
        }
    }

    private static function methodExists($controller, string $method, string $classe)
    {
        if (!method_exists($controller, $method)) {
            throw new Exception("Metodo {$method} não existe em {$classe}");
        }
    }
}