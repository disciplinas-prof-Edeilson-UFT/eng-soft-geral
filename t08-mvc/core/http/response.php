<?php 
namespace Conex\MiniFramework\http;
use Conex\MiniFramework\mvc\View;

/**
 * Class Response - Sistema de geração e envio de respostas HTTP
 * 
 * Classe responsável por gerenciar todas as respostas enviadas ao client,
 * incluindo páginas de erro padronizadas, redirecionamentos e controle
 * de códigos de status HTTP. Centraliza a lógica de resposta da aplicação
 * 
 * Funcionalidades principais:
 * - Geração automática de páginas de erro (404, 403, 500)
 * - Controle de códigos de status HTTP
 * - Sistema de redirecionamento
 * - Integração com sistema de views para renderização
 * - Terminação controlada da execução
 * 
 * @package Conex\MiniFramework\http
 * @version 1.0
 */
class Response
{
    /**
     * Gera e exibe página de erro com código de status HTTP específico
     * 
     * Método responsável por tratar erros HTTP de forma padronizada,
     * definindo o código de status apropriado e renderizando views
     * de erro customizadas. Termina a execução do script após exibir
     * o erro para evitar processamento adicional desnecessário
     * 
     * Códigos de erro suportados:
     * - 404: Página não encontrada
     * - 403: Acesso proibido/não autorizado
     * - 500: Erro interno do servidor
     * 
     * Fluxo de processamento:
     * 1. Define código de status HTTP via http_response_code()
     * 2. Prepara parâmetros com mensagem de erro
     * 3. Renderiza view de erro específica através do sistema View
     * 4. Exibe conteúdo renderizado ou mensagem padrão
     * 5. Termina execução com exit para evitar output adicional
     * 
     * @param int $statusCode Código de status HTTP (404, 403, 500, etc.)
     * 
     * @return never Este método sempre termina a execução com exit
     * 
     * @example
     * // Para erro 404 - Página não encontrada
     * Response::errorView(404);
     * // Renderiza view/error/404.php e termina execução
     * 
     * // Para erro 500 - Erro interno
     * Response::errorView(500);
     * // Renderiza view/error/500.php e termina execução
     */
    public static function errorView(int $statusCode): never
    {
        http_response_code($statusCode);
        $params = ['message' => 'Erro ' . $statusCode];

        $defaultHeaders = [
            404 => View::render('error/404', $params),
            403 => View::render('error/403', $params),
            500 => View::render('error/500', $params),
        ];
        
        echo $defaultHeaders[$statusCode] ?? 'Status code ' . $statusCode . ' não mapeado';
        exit;
    }

    /**
     * Executa redirecionamento HTTP para URL especificada com verificações de segurança
     * 
     * Método para redirecionar o usuário para outra página ou URL,
     * utilizando header HTTP Location. Inclui verificações de segurança
     * para evitar erros quando headers já foram enviados e termina
     * execução automaticamente para garantir redirecionamento efetivo
     * 
     * Comportamento:
     * - Verifica se headers já foram enviados antes de tentar redirecionar
     * - Envia header 'Location' com URL de destino
     * - Termina execução automaticamente com exit
     * - Registra erro em log se headers já foram enviados
     * 
     * Tipos de redirecionamento suportados:
     * - Interno: '/profile/123', '/dashboard'
     * - Externo: 'https://exemplo.com'
     * - Relativo: '../admin', './edit'
     * 
     * @param string $url URL de destino para redirecionamento
     * 
     * @return never Este método sempre termina a execução com exit
     * 
     * @example
     * // Redirecionamento interno após login
     * Response::redirect('/dashboard');
     * 
     * // Redirecionamento para formulário após erro
     * Response::redirect('/profile/edit');
     * 
     * // Redirecionamento externo
     * Response::redirect('https://exemplo.com/docs');
     */
    public static function redirect(string $url): never
    {
        if (headers_sent($file, $line)) {
            error_log("Headers already sent in $file:$line - Cannot redirect to $url");
            exit;
        }
        
        header('Location: ' . $url);
        exit;
    }
}