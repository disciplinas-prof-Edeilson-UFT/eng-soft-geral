<?php 
namespace Conex\MiniFramework\mvc;
class View{
    
    /*Renderiza uma view com um layout padrão e parametros 
    */

    public static function render(string $view, $params = []){

        $layoutContent = self::layoutContent('main');
        $viewContent = self::renderOnlyView($view, $params);  
        
        $content = str_replace('{{content}}', $viewContent, $layoutContent);
        return $content;
    }

    public static function layoutContent(string $layout){
        $path = __DIR__ . "/../../view/layouts/{$layout}.php";
        if (!file_exists($path)){
            throw new \Exception("layout não encontrado");
            
        }

        ob_start();
        include_once $path;
        return ob_get_clean();
    }

    public static function renderOnlyView(string $view, $params = []){
        $path = __DIR__ . "/../../view/{$view}.php";
        if (!file_exists($path)){
            throw new \Exception("View não encontrada");
        }

        foreach ($params as $key => $value) {
            $$key = $value;
        }
        ob_start();
        include_once $path;
        
        return ob_get_clean();
    }
}