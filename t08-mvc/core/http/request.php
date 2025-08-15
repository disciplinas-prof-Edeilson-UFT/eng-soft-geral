<?php
namespace Conex\MiniFramework\http; 

use Exception;
use Conex\MiniFramework\mvc\Router;

/**
 * Class Request - Sistema de captura e processamento de requisições HTTP
 * 
 * Classe responsável por abstrair e facilitar o acesso aos dados das requisições
 * HTTP recebidas pela aplicação. Oferece métodos seguros para capturar
 * dados de formulários, query strings, métodos HTTP e outros componentes
 * da requisição de forma sanitizada e validada
 * 
 * Funcionalidades principais:
 * - Detecção do método HTTP (GET, POST, PUT, DELETE)
 * - Extração segura de dados POST e GET
 * - Sanitização automática de inputs
 * - Validação de existência de campos
 * - Processamento de arrays completos de dados
 * 
 * @package Conex\MiniFramework\http
 * @version 1.0
 */
class Request
{
    /**
     * Obtém o método HTTP da requisição atual
     * 
     * Captura o método HTTP enviado pelo browser através da superglobal
     * $_SERVER['REQUEST_METHOD'] e o normaliza em lowercase para
     * facilitar comparações e processamento posterior
     * 
     * Métodos suportados: GET, POST, PUT, DELETE, PATCH, OPTIONS, HEAD
     * 
     * @return string Método HTTP em lowercase (ex: 'get', 'post', 'put')
     * 
     * @example
     * // Para requisição POST /users
     * // Retorna: "post"
     * 
     * // Para requisição GET /profile/123
     * // Retorna: "get"
     */
    public static function getMethod(): string
    {
        return strtolower($_SERVER['REQUEST_METHOD']);
    }

    /**
     * Obtém o caminho (path) da requisição sem query strings
     * 
     * Método que delega para Router::getUri() para evitar duplicação
     * de código. Extrai apenas a parte do caminho da URI, removendo
     * parâmetros de query string usando parse_url() de forma robusta
     * 
     * Reutiliza funcionalidade consolidada no Router para garantir
     * consistência e eliminar redundância no framework
     * 
     * @return string Caminho da URI sem query strings (ex: '/users/123')
     * 
     * @example
     * // $_SERVER['REQUEST_URI'] = "/users/123?page=2&sort=name"
     * // Retorna: "/users/123"
     * 
     * // $_SERVER['REQUEST_URI'] = "/profile"
     * // Retorna: "/profile"
     * 
     * @see Router::getUri() Implementação consolidada
     */
    public static function getPath(): string
    {
        return Router::getUri();
    }

    /**
     * Obtém campo específico de dados POST com sanitização opcional
     * 
     * Método seguro para capturar dados enviados via POST, com validação
     * de método HTTP e sanitização automática para prevenir ataques XSS 
     * 
     * Validações realizadas:
     * - Verifica se requisição é realmente POST
     * - Valida existência do campo solicitado
     * - Aplica sanitização por padrão usando FILTER_SANITIZE_SPECIAL_CHARS
     * 
     * @param string $input Nome do campo POST a ser capturado
     * @param bool $sanitize Se true, aplica sanitização (por padrão: true)
     * 
     * @return string Valor do campo POST, sanitizado se solicitado
     * 
     * @throws Exception Se não for requisição POST
     * @throws Exception Se campo não existir nos dados POST
     * 
     * @example
     * // Para $_POST['username'] = '<script>alert("xss")</script>'
     * // Request::input('username') 
     * // Retorna: "&lt;script&gt;alert(&quot;xss&quot;)&lt;/script&gt;"
     * 
     * // Request::input('username', false) 
     * // Retorna: '<script>alert("xss")</script>'
     */
    public static function input(string $input, bool $sanitize = true)
    {
        if(self::getMethod() !== 'post'){throw new Exception("Nao e um metodo do tipo POST");}
        $value = $_POST[$input] ?? null;

        if ($value === null) {throw new Exception("Campo {$input} nao encontrado");}

        return $sanitize ? filter_var($value, FILTER_SANITIZE_SPECIAL_CHARS) : $value;
    }

    /**
     * Obtém campo específico de query string GET com sanitização opcional
     * 
     * Método seguro para capturar parâmetros de URL enviados via GET,
     * com validação de método HTTP e sanitização para prevenir ataques.
     * Ideal para capturar filtros, paginação e outros parâmetros de URL.
     * 
     * Validações realizadas:
     * - Verifica se requisição é realmente GET
     * - Valida existência do parâmetro na query string
     * - Aplica sanitização por padrão usando FILTER_SANITIZE_SPECIAL_CHARS
     * 
     * @param string $query    Nome do parâmetro GET a ser capturado
     * @param bool   $sanitize Se true, aplica sanitização (padrão: true)
     * 
     * @return string Valor do parâmetro GET, sanitizado se solicitado
     * 
     * @throws Exception Se não for requisição GET
     * @throws Exception Se parâmetro não existir na URL
     * 
     * @example
     * // Para URL /search?term=<script>&page=2
     * // Request::query('term')
     * // Retorna: "&lt;script&gt;"
     * 
     * // Request::query('page')
     * // Retorna: "2"
     */
    public static function query(string $query, bool $sanitize = true)
    {
        if(self::getMethod() !== 'get'){throw new Exception("Nao e um metodo do tipo get");} 
        $value= $_GET[$query] ?? null;

        if ($value === null) {throw new Exception("Campo {$query} nao encontrado na URL");}

        return $sanitize ? filter_var($value, FILTER_SANITIZE_SPECIAL_CHARS) : $value;

    }
}