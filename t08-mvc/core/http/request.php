<?php
namespace Conex\MiniFramework\http; 

use Exception;

class Request
{
    /*Capturar dados da requisição HTTP
    
    Método (GET/POST)
    Query strings
    Dados de formulários
    */

    //obtem o método da requisição: GET, POST, PUT, DELETE, (são enviados pelo browser)
    public static function getMethod(){
        return strtolower($_SERVER['REQUEST_METHOD']);
    }

    //obtem o caminho da requisição (URI): /users/1 no browser
    public static function getPath(){
        $path = $_SERVER['REQUEST_URI'];
        $position = strpos($path, '?');
        return $position ? substr($path, 0, $position) : $path;
    }

    // obtem um campo específico do POST
    //Request::input('name') representa $_POST['name']
    public static function input(string $input, bool $sanitize = true)
    {
        if(self::getMethod() !== 'post'){
            throw new Exception("Nao e um metodo do tipo POST");
        }
        $value = $_POST[$input] ?? null;

        if ($value === null) {
            throw new Exception("Campo {$input} não encontrado");
        }

        return $sanitize ? filter_var($value, FILTER_SANITIZE_SPECIAL_CHARS) : $value;
    }

    //recupera um campo específico do GET
    // Request::query('name') representa um $_GET['name']
    public static function query(string $query, bool $sanitize = true)
    {
        if(self::getMethod() !== 'get'){
            throw new Exception("Nao e um metodo do tipo get");
        }
        $value = $_GET[$query] ?? null;

        if ($value === null) {
            throw new Exception("Campo {$query} não encontrado na URL");
        }

        return $sanitize ? filter_var($value, FILTER_SANITIZE_SPECIAL_CHARS) : $value;

    }

    // ex: Request::getAllPost()
    /* Retorna:
        Array (
            [username] => abc123
            [idade] => 123
    */
    public static function getAllPost(bool $sanitize = true): array {
        if (self::getMethod() !== 'post') {
            return [];
        }
        
        if (!$sanitize) {
            return $_POST;
        }
        
        return array_map(fn($value) => filter_var($value, FILTER_SANITIZE_SPECIAL_CHARS), $_POST);
    }

    // ex: Request::getAllQuery()
    /* Retorna:
        Array (
            [nome] => abc
            [categoria] => teste
            [page] => 2
        )
    */
    public static function getAllQuery(bool $sanitize = true): array {
        if (self::getMethod() !== 'get') {
            return [];
        }

        if (!$sanitize) {
            return $_GET;
        }

        return array_map(fn($value) => filter_var($value, FILTER_SANITIZE_SPECIAL_CHARS), $_GET);
    }

}