<?php 
namespace core\mvc;
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
        ob_start();
        include_once __DIR__ . "/../../view/layouts/{$layout}.php";
        return ob_get_clean();
    }

    public static function renderOnlyView(string $view, $params = []){
        foreach ($params as $key => $value) {
            $$key = $value;
        }
        ob_start();
        include_once __DIR__ . "/../../view/{$view}.php";
        
        return ob_get_clean();
    }
}