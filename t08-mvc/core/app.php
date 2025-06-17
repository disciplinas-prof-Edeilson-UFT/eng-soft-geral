<?php 
namespace Conex\MiniFramework;

use Conex\MiniFramework\mvc\Router;
use Conex\MiniFramework\http\Request;
use Conex\MiniFramework\http\Response;
use Conex\MiniFramework\mvc\Controller;
use Conex\MiniFramework\mvc\View;

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