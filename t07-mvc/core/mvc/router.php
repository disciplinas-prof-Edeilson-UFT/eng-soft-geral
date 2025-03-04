<?php 
namespace core\mvc;

use Routes;
use core\http\Request;
use core\http\Response;
use core\mvc\helpers\Parameters;
class Router {
    /*
        router deve ser capaz de identificar se a rota é estática ou dinamica e despachar para o controller correto
    */

    public Routes $routes;
    private string $uri;
    private string $method;
    private array $routesRegistered;

    public function __construct()
    {
        $this->uri = Parameters::getUri();
        $this->method = Request::getMethod();
        $this->routesRegistered = Routes::getRoutes();
    }


    public function staticRoute(){

        if (array_key_exists($this->uri, $this->routesRegistered[$this->method])) {
            return $this->routesRegistered[$this->method][$this->uri];
        }

        return null;
    }

    private function dynamicRoute() {
        $routerRegisteredFound = null; 
        $matches = [];

        foreach ($this->routesRegistered[$this->method] as $index => $route) {
            $paramPattern = preg_replace('/{([a-zA-Z0-9_]+)}/', '([^/]+)', $index);
            $regex = str_replace('/', '\/', ltrim($paramPattern, '/'));

            if ($index !== '/' && preg_match("/^$regex$/", trim($this->uri, '/'), $matches)) {
                $routerRegisteredFound = $route;

                array_shift($matches);
                $_REQUEST['router_params'] = $matches;
                break;
            }
        }
        return $routerRegisteredFound;
        
    }

    public function dispatch(){
        $router = $this->staticRoute();
        if ($router) {
            Controller::execute($router);
            return;
        }

        $router = $this->dynamicRoute();
        if ($router) {
            Controller::execute($router);
            return;
        }

        Response::errorView(404);
        exit();
    }

}