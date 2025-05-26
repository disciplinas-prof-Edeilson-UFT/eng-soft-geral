<?php 
namespace core;

use core\mvc\Router;
use core\http\Request;
use core\http\Response;
use core\mvc\Controller;
use core\mvc\View;

class App{
    public Router $router;
    public Request $request;
    public Response $response;
    public ?Controller $controller = null;
    public View $view;
    
    public function __construct(){
        $this->request = new Request();
        $this->router = new Router();
        $this->response = new Response();
        $this->view = new View();
    }

    public function run(){
        $this->router->dispatch();
    }
}