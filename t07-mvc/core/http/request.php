<?php
namespace core\http; 

use Exception;

class Request
{
    /*Capturar dados da requisição HTTP
    
    Método (GET/POST)
    Query strings
    Dados de formulários
    Headers
    */
    public static function getMethod()
    {
        return strtolower($_SERVER['REQUEST_METHOD']);
    }

    public static function getPath()
    {
        $path = $_SERVER['REQUEST_URI'];
        $position = strpos($path, '?');

        if (!$position) {
            return $path;
        }
        return substr($path, 0, $position);
    }

    //obtem o corpo da requisição com todos os dados do POST ou GET em um array
    public static function getBody(): array{
        $body = [];
        if (self::getMethod() === 'get'){
            foreach ($_GET as $key => $value){
                $body[$key] = filter_input(INPUT_GET, $key, FILTER_SANITIZE_SPECIAL_CHARS);
            }
        }
        if (self::getMethod() === 'post'){
            foreach ($_POST as $key => $value){
                $body[$key] = filter_input(INPUT_POST, $key, FILTER_SANITIZE_SPECIAL_CHARS);
            }
        }
        return $body;
    }

    // obtem um campo específico do POST
    public static function input(string $input)
    {
        if(self::getMethod() !== 'post'){
            throw new Exception("Não é um metodo do tipo POST");
        }
        $body = self::getBody();

        return $body[$input] ?? throw new Exception("Campo {$input} não encontrado no body da requisição");
    }

    //recupera um campo específico do GET
    public static function query(string $query)
    {
        if(self::getMethod() !== 'get'){
            throw new Exception("Não é um metodo do tipo get");
        }
        $body = self::getBody();

        return $body[$query] ?? throw new Exception("O campo {$query} não foi encontrado na url"); 

    }
}