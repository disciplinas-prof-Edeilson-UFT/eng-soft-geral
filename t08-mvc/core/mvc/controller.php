<?php
namespace Conex\MiniFramework\mvc;

use Exception;
use Conex\MiniFramework\mvc\helpers\Parameters;

/**
 * Classe Controller - Sistema de despacho e execução de controllers
 * 
 * Responsável por receber informações de roteamento do Router e executar
 * o controller e método correspondentes. Gerencia a instanciação de classes,
 * validação de métodos, extração de parâmetros e invocação dinâmica
 * 
 * Esta classe atua como uma ponte entre o sistema de roteamento e os
 * controllers específicos da aplicação, garantindo que as requisições
 * sejam direcionadas corretamente e executadas com segurança
 * 
 * @package Conex\MiniFramework\mvc
 * @version 1.0
 */
class Controller
{
    /**
     * Executa o método do controller correspondente à rota acessada
     * 
     * Método principal que coordena todo o processo de execução de um controller.
     * Recebe uma string no formato "Controller@method" do sistema de roteamento
     * e executa o fluxo completo de validação, instanciação e invocação.
     * 
     * Fluxo de execução:
     * 1. Valida formato da string de rota (deve conter '@')
     * 2. Separa controller e método através do delimitador '@'
     * 3. Constrói nome completo da classe com namespace
     * 4. Verifica se a classe do controller existe
     * 5. Instancia o controller
     * 6. Verifica se o método existe na instância
     * 7. Extrai parâmetros da rota através de Parameters::getRouterParams()
     * 8. Executa o método com os parâmetros via call_user_func_array()
     * 
     * @param string $router String no formato "Controller@method" (ex: "site\UserController@show")
     * 
     * @throws Exception Se formato da rota for inválido
     * @throws Exception Se controller não existir
     * @throws Exception Se método não existir no controller
     * 
     * @example
     * // Para rota "site\ProfileController@show":
     * // 1. Valida formato (contém '@')
     * // 2. controller = "site\ProfileController", method = "show"
     * // 3. className = "src\controllers\site\ProfileController"
     * // 4. Instancia ProfileController
     * // 5. Executa ProfileController->show($params)
     */
    public static function execute(string $router)
    {
        self::validateRouteFormat($router);
        
        list($controller, $method) = explode('@', $router);
        
        $className = "src\\controllers\\" . $controller;
        
        self::classExists($className);

        $controllerInstance = new $className();
    
        self::methodExists($controllerInstance, $method, $className);
        
        $params = Parameters::getRouterParams($router);
        call_user_func_array([$controllerInstance, $method], $params);
    }

    /**
     * Valida o formato da string de rota
     * 
     * Verifica se a string de rota fornecida pelo sistema de roteamento
     * está no formato correto "Controller@method". O delimitador '@' é
     * obrigatório para separar o nome do controller do nome do método.
     * 
     * @param string $router String de rota a ser validada
     * 
     * @throws Exception Se a string não contiver o delimitador '@'
     * 
     * @example
     * validateRouteFormat("UserController@show")     // Válido
     * validateRouteFormat("UserController.show")     // Exception
     * validateRouteFormat("UserController")          // Exception
     */
    private static function validateRouteFormat(string $router)
    {
        if (!str_contains($router, '@')) {
            throw new Exception("Formato de rota inválido!  correto: Controller@method");
        }
    }

    /**
     * Verifica se a classe do controller existe
     * 
     * Valida se a classe especificada existe e pode ser instanciada.
     * Utiliza a função nativa class_exists() do PHP para verificar
     * se a classe foi carregada corretamente através do autoloader.
     * 
     * @param string $classe Nome completo da classe incluindo namespace
     *                      (ex: "src\controllers\site\UserController")
     * 
     * @throws Exception Se a classe não existir ou não puder ser carregada
     * 
     * @example
     * classExists("src\controllers\site\UserController")  // Se classe existir
     * classExists("src\controllers\NonExistent")          // Exception
     */
    private static function classExists(string $classe)
    {
        if (!class_exists($classe)) {
            throw new Exception("Controller {$classe} não encontrado");
            //Response::errorView(404);
        }
    }

    /**
     * Verifica se o método existe na instância do controller
     * 
     * Valida se o método especificado existe na instância do controller
     * e pode ser invocado. Utiliza method_exists() para verificar a
     * disponibilidade do método na classe instanciada.
     * 
     * Esta validação é crucial para evitar erros fatais durante a
     * execução dinâmica de métodos via call_user_func_array().
     * 
     * @param object $controller Instância do controller já criada
     * @param string $method     Nome do método a ser verificado
     * @param string $classe     Nome da classe para mensagens de erro
     * 
     * @throws Exception Se o método não existir na instância do controller
     * 
     * @example
     * // $userController = new UserController();
     * methodExists($userController, "show", "UserController")     // ✅ Se método existir
     * methodExists($userController, "nonExistent", "UserController") // ❌ Exception
     */
    private static function methodExists($controller, string $method, string $classe)
    {
        if (!method_exists($controller, $method)) {
            throw new Exception("Metodo {$method} não existe em {$classe}");
        }
    }
}