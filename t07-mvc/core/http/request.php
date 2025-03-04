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

    //obtem o método da requisição: GET, POST, PUT, DELETE, (são enviados pelo browser)
    public static function getMethod()
    {
        return strtolower($_SERVER['REQUEST_METHOD']);
    }

    //obtem o caminho da requisição (URI): /users/1 no browser
    public static function getPath()
    {
        $path = $_SERVER['REQUEST_URI'];
        $position = strpos($path, '?');

        if (!$position) {
            return $path;
        }
        return substr($path, 0, $position);
    }

    //obtem o corpo da requisição com todos os dados do POST ou GET em um array, ex: $_POST['name'] = 'abc' => ['name' => 'abc']
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
    //Request::input('name') representa $_POST['name']
    public static function input(string $input)
    {
        if(self::getMethod() !== 'post'){
            throw new Exception("Não é um metodo do tipo POST");
        }
        $body = self::getBody();

        return $body[$input] ?? throw new Exception("Campo {$input} não encontrado no body da requisição");
    }

    //recupera um campo específico do GET
    // Request::query('name') representa um $_GET['name']
    public static function query(string $query)
    {
        if(self::getMethod() !== 'get'){
            throw new Exception("Não é um metodo do tipo get");
        }
        $body = self::getBody();

        return $body[$query] ?? throw new Exception("O campo {$query} não foi encontrado na url"); 

    }

}