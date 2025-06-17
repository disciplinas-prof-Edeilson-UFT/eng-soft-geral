<?php 
namespace Conex\MiniFramework\http;
use Conex\MiniFramework\mvc\View;
class Response{

    /* class response envia a resposta ao cliente, seja ela um conteúdo, um redirecionamento ou um erro
    */

    public static function errorView(int $statusCode)
    {
        http_response_code($statusCode);
        $params = [
            'message' => 'Erro ' . $statusCode,
        ];
        $defaultHeaders = [
            404 => View::render('error/404', $params),
            403 => View::render('error/403', $params),
            500 => View::render('error/500', $params),
        ];
        
        echo $defaultHeaders[$statusCode] ?? 'status code' . $statusCode . 'não mapeado';
        exit;
    }

    public function redirect(string $url){
        header('Location: ' . $url);
    }   


}