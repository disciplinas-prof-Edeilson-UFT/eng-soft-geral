<?php 
namespace core\mvc;

use Routes;
use core\http\Request;
use core\http\Response;
use core\mvc\helpers\Parameters;
class Router {
    /*
        router deve ser capaz de identificar se a rota é estática ou dinamica e despachar para o controller correto os seus parametros
    */

    public Routes $routes;
    private string $uri;
    private string $method;
    private array $routerRegistered;

    public function __construct()
    {
        $this->uri = Parameters::getUri();
        $this->method = Request::getMethod();
        $this->routerRegistered = Routes::getRouter();
        $this->processRouteGroups();
    }

    //verifica se a URI estatica existe corresponde a registrada no routes.php e a retorna 
    public function staticIndividualRoute(){

        if (array_key_exists($this->uri, $this->routerRegistered[$this->method])) {
            return $this->routerRegistered[$this->method][$this->uri];
        }
        return null;
    }

    //percorr URIs dinamicas ( /users/{id}) e verifica se a rota está registrada no routes.php por meio de regex e a retorna
    private function dynamicIndividualRoute() {
        $routerRegistered = null; 
        $matches = [];

        foreach ($this->routerRegistered[$this->method] as $index => $route) {
            $paramPattern = preg_replace('/{([a-zA-Z0-9_]+)}/', '([^/]+)', $index);
            $regex = str_replace('/', '\/', ltrim($paramPattern, '/'));

            if ($index !== '/' && preg_match("/^$regex$/", trim($this->uri, '/'), $matches)) {
                $routerRegistered = $route;

                array_shift($matches);
                $_REQUEST['router_params'] = $matches;
                break;
            }
        }
        return $routerRegistered;
        
    }

    //percorre os grupos de rotas no routes.php e as registra no routerRegistered para serem despachadas para o controller correspondente
    private function processRouteGroups() {
        if(!isset($this->routerRegistered['groups'])) {
            return;
        }
    
        foreach ($this->routerRegistered['groups'] as $prefix => $group) {
            foreach ($group as $method => $routes) {
                if (!isset($this->routerRegistered[$method])) {
                    $this->routerRegistered[$method] = [];
                }
                
                foreach ($routes as $path => $controller) {
                    $fullPath = rtrim($prefix, '/') . '/' . ltrim($path, '/');
                    $fullPath = '/' . ltrim($fullPath, '/');
                    
                    $this->routerRegistered[$method][$fullPath] = $controller;
                }
            }
        }
        unset($this->routerRegistered['groups']);
    }

    //despacha a rota para o controller correto 
    public function dispatch(){
        $router = $this->staticIndividualRoute();
        if ($router) {
           //echo "Rota estática encontrada: " . $router . "<br>";
            Controller::execute($router);
            return;
        }

        $router = $this->dynamicIndividualRoute();
        if ($router) {
            //echo "Rota dinamica encontrada: " . $router . "<br>";
            Controller::execute($router);
            return;
        }

        Response::errorView(404);
        exit();
    }

}