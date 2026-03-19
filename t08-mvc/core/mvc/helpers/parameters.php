<?php
namespace Conex\MiniFramework\mvc\helpers; 

use Routes;
use Exception;  
use Conex\MiniFramework\http\Request;
use Conex\MiniFramework\mvc\Router;

/**
 * Class Parameters - Sistema de extração de parâmetros de rota
 * 
 * Um Helper responsável por extrair e processar parâmetros dinâmicos de URLs
 * baseados em padrões de rota definidos. Utiliza funcionalidades do Router
 * para evitar duplicação de código e manter consistência
 * 
 * @package Conex\MiniFramework\mvc\helpers
 * @version 1.0
 */
class Parameters
{
    /**
     * Localiza rota e extrai parâmetros para controller específico
     * 
     * Método principal que coordena todo o processo de extração de parâmetros.
     * Busca nas configurações de rota pelo controller especificado,
     * reconstrói padrão completo com prefixos de grupo e executa
     * extração de parâmetros reutilizando métodos do Router
     * 
     * @param string $controllerMethodPath Path completo "Controller@method"
     * 
     * @return array Array indexado com valores dos parâmetros extraídos
     */
    public static function getRouterParams(string $controllerMethodPath): array
    {
        $routes = Routes::getRouter();
        $method = Request::getMethod(); 
        $currentUri= Router::getUri();
        $params= [];

        if (isset($routes['groups'])) {
            foreach ($routes['groups'] as $groupPrefix => $group) {
                if (isset($group[$method])) {
                    foreach ($group[$method] as $route => $controller) {
                        if ($controller === $controllerMethodPath) {
                            $fullRoutePattern = self::buildFullRoute($groupPrefix, $route);
                            
                            if (Router::matchRoute($fullRoutePattern, $currentUri)) {
                                $params = Router::extractRouteParams($fullRoutePattern, $currentUri);
                                break 2; 
                            }
                        }
                    }
                } 
            }
        }

        return array_values($params);
    }

    /**
     * Constrói rota completa com prefixo do grupo
     * 
     * @param string $groupPrefix Prefixo do grupo de rotas
     * @param string $route Rota específica
     * 
     * @return string Rota completa formatada
     */
    private static function buildFullRoute(string $groupPrefix, string $route): string{
        if ($groupPrefix === '') {return $route;}
        
        return '/' . trim($groupPrefix, '/') . '/' . trim($route, '/');
    }
}