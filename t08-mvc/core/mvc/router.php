<?php
namespace Conex\MiniFramework\mvc;
use Routes;
use Exception;
use Conex\MiniFramework\http\Request;
use Conex\MiniFramework\http\Response;
use Conex\MiniFramework\mvc\Controller;

/**
 * Classe Router - Sistema de roteamento do Mini-Framework MVC
 * 
 * Responsável por mapear requisições HTTP para controllers específicos,
 * processar middleware, extrair parâmetros de URL e gerenciar o fluxo
 * de despacho de requisições na aplicação
 * 
 * @package Conex\MiniFramework\mvc
 * @version 1.0
 */
class Router{
    /**
     * Array contendo todas as rotas configuradas da aplicação
     * @var array
     */
    private $routes;
    
    /**
     * Construtor da classe Router
     * 
     * Inicializa o sistema de roteamento carregando todas as rotas
     * definidas no arquivo Routes.php através do método estático getRouter()
     */
    public function __construct()
    {
        $this->routes = Routes::getRouter();
    }

    /**
     * Processa todas as rotas configuradas buscando correspondência
     * 
     * Este método é o núcleo do sistema de roteamento. Itera através de todos
     * os grupos de rotas definidos, verifica prefixos, calcula caminhos sem
     * prefixo e busca correspondências exatas ou dinâmicas.
     * 
     * Fluxo de processamento:
     * 1. Verifica se existem grupos de rotas configurados
     * 2. Para cada grupo, calcula o prefixo e remove da URI
     * 3. Busca correspondência exata no mapeamento entre método e URI
     * 4. Se não encontrar, busca correspondência com parâmetros dinâmicos
     * 5. Retorna controller e middlewares ou null se não encontrar
     * 
     * @param array  $routes Array com todas as rotas configuradas
     * @param string $method Método HTTP da requisição (GET, POST, PUT, DELETE)
     * @param string $uri URI limpa da requisição (ex: /profile/123)
     * 
     * @return array|null Array com 'controller' e 'middlewares' se encontrar rota,
     *                    null se não encontrar correspondência
     * 
     * @example
     * // Para URI /profile/123 e método GET:
     * // Retorna: ['controller' => 'site\ProfileController@show', 'middlewares' => ['auth']]
     */
    private function processAllRoutes($routes, $method, $uri)
    {
        if (!isset($routes['groups'])) {
            return null;
        }
        
        foreach ($routes['groups'] as $prefix => $groupRoutes) {
            if ($prefix === '') {
                $prefixPath = '';
                $routeWithoutPrefix = $uri;
            } else {
                $prefixPath = '/' . trim($prefix, '/');
                if (strpos($uri, $prefixPath) !== 0) {
                    continue;
                }
                $routeWithoutPrefix = substr($uri, strlen($prefixPath));
            }
            
            if (isset($groupRoutes[$method][$routeWithoutPrefix])) {
                return [
                    'controller' => $groupRoutes[$method][$routeWithoutPrefix],
                    'middlewares' => $groupRoutes['middleware'] ?? []
                ];
            }
            
            if (isset($groupRoutes[$method])) {
                foreach ($groupRoutes[$method] as $route => $controller) {
                    if ($this->matchRoute($route, $routeWithoutPrefix)) {
                        return [
                            'controller' => $controller,
                            'middlewares' => $groupRoutes['middleware'] ?? []
                        ];
                    }
                }
            }
        }
        return null;
    }

    /**
     * Aplica middleware configurados para a rota
     * 
     * Executa sequencialmente todos os middlewares definidos para uma rota
     * específica. Cada middleware pode implementar validações, autenticação,
     * autorização ou outras lógicas transversais que devem ser executadas
     * antes do controller principal ser chamado
     * 
     * Middlewares suportados:
     * - 'auth': Verificação de autenticação de usuário
     * - Outros middlewares podem ser adicionados facilmente
     * 
     * @param array $middlewares Array de strings com nomes dos middlewares
     * 
     * @example
     * // Para middlewares ['auth', 'admin']:
     * // Executa checkAuth() e depois checkAdmin()
     */
    private function applyMiddlewares($middlewares){
        foreach ($middlewares as $middleware) {
            switch ($middleware) {
                case 'auth':
                    $this->checkAuth();
                    break;
                default:
                    break;
            }
        }
    }

    /**
     * Verifica autenticação do usuário através de sessão
     * 
     * Middleware de autenticação que valida se existe um usuário logado
     * verificando a existência e validade do user_id na sessão PHP.
     * Se o usuário não estiver autenticado, redireciona para página de login.
     * 
     * Validações realizadas:
     * - Inicia sessão PHP se não estiver ativa
     * - Verifica existência de $_SESSION['user_id']
     * - Valida se user_id não está vazio
     * - Redireciona para /auth/login se inválido
     * 
     * @throws Exception Termina execução com header de redirecionamento
     * 
     * @example
     * // Se $_SESSION['user_id'] = 123: Permite continuar
     * // Se $_SESSION['user_id'] não existe: Redireciona para /auth/login
     */
    private function checkAuth(){
        session_start();
        if (!isset($_SESSION['user_id']) || empty($_SESSION['user_id'])) {
            header('Location: /auth/login');
            exit;
        }
    }

    /**
     * Verifica correspondência entre padrão de rota e URI solicitada
     * 
     * Algoritmo de matching que compara um padrão de rota (que pode conter
     * parâmetros dinâmicos entre chaves) com uma URI específica. Suporta
     * parâmetros dinâmicos no formato {nome_parametro}.
     * 
     * Algoritmo de correspondência:
     * 1. Divide padrão e URI em segmentos por '/'
     * 2. Verifica se número de segmentos é igual
     * 3. Para cada segmento:
     *    - Se é parâmetro {nome}: aceita qualquer valor
     *    - Se é texto: deve corresponder exatamente
     * 
     * @param string $routePattern Padrão da rota (ex: /user/{id}/posts/{post_id})
     * @param string $uri          URI a ser testada (ex: /user/123/posts/456)
     * 
     * @return bool true se URI corresponde ao padrão, false caso contrário
     * 
     * @example
     * matchRoute('/user/{id}', '/user/123')     // retorna true
     * matchRoute('/user/{id}', '/user/123/abc') // retorna false
     * matchRoute('/admin', '/admin')            // retorna true
     */
    private function matchRoute($routePattern, $uri)
    {
        $routeParts = explode('/', trim($routePattern, '/'));
        $uriParts = explode('/', trim($uri, '/'));
        
        if (count($routeParts) !== count($uriParts)) {
            return false;
        }
        
        foreach ($routeParts as $index => $segment) {
            if (preg_match('/^\{([a-zA-Z0-9_]+)\}$/', $segment)) {
                continue;
            }
            if ($segment !== $uriParts[$index]) {
                return false;
            }
        }
        
        return true;
    }

    /**
     * Obtém URI limpa da requisição HTTP
     * 
     * Processa a REQUEST_URI removendo query strings e normalizando
     * a barra final. Garante que a URI esteja em formato consistente
     * para processamento pelo sistema de roteamento.
     * 
     * Processamento realizado:
     * - Extrai apenas o path da URI (remove query strings)
     * - Remove barra final exceto para URI raiz
     * - Retorna '/' para URI vazia
     * 
     * @return string URI limpa e normalizada
     * 
     * @example
     * // $_SERVER['REQUEST_URI'] = '/profile/123?tab=posts'
     * // Retorna: '/profile/123'
     * 
     * // $_SERVER['REQUEST_URI'] = '/admin/'
     * // Retorna: '/admin'
     * 
     * // $_SERVER['REQUEST_URI'] = '/'
     * // Retorna: '/'
     */
    private function getCleanUri(){
        $uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        return rtrim($uri, '/') ?: '/';
    }

    /**
     * Método principal de despacho de requisições
     * 
     * Ponto de entrada principal do sistema de roteamento que coordena
     * todo o fluxo de processamento de uma requisição HTTP. Gerencia
     * a sequência completa desde análise da requisição até execução
     * do controller ou tratamento de erros.
     * 
     * Fluxo de execução:
     * 1. Obtém método HTTP da requisição (GET, POST, etc.)
     * 2. Limpa e normaliza a URI
     * 3. Busca rota correspondente em todas as rotas configuradas
     * 4. Se não encontrar rota: retorna erro 404
     * 5. Se encontrar rota: aplica middlewares configurados
     * 6. Executa controller através de Controller::execute()
     * 7. Captura exceções e retorna erro 500 se necessário
     * 
     * @throws Exception Captura e converte em erro 500 para o usuário
     * 
     * @example
     * // Para requisição GET /profile/123:
     * // 1. method = 'get', uri = '/profile/123'
     * // 2. Encontra ProfileController@show com middleware ['auth']
     * // 3. Executa checkAuth()
     * // 4. Executa ProfileController->show()
     */
    public function dispatch()
    {
        $method = Request::getMethod();
        $uri = $this->getCleanUri();
        $result = $this->processAllRoutes($this->routes, $method, $uri);
        
        if ($result === null) {
            Response::errorView(404);
            return;
        }
        
        try {
            if (isset($result['middlewares'])) {
                $this->applyMiddlewares($result['middlewares']);
            }
            
            Controller::execute($result['controller']);
        } catch (Exception $e) {
            Response::errorView(500);
        }
    }
}