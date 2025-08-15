<?php 
namespace Conex\MiniFramework\mvc;

/**
 * Class View - Sistema de renderização de templates e layouts
 * 
 * Responsável por processar templates PHP, gerenciar layouts,
 * injetar dados nas views e gerar conteúdo HTML final.
 * Proporciona suporte a layouts hierárquicos e isolamento de variáveis
 * 
 * A classe utiliza output buffering para capturar conteúdo
 * gerado pelos templates e permite composição de layouts
 * através de placeholders
 * 
 * @package Conex\MiniFramework\mvc
 * @version 1.0
 */
class View{

    /**
     * Renderiza uma view com layout principal
     * 
     * Método principal de renderização que combina uma view específica
     * com o layout principal da aplicação. Utiliza output buffering para
     * capturar o conteúdo gerado e substitui placeholders no layout
     * 
     * Fluxo de renderização:
     * 1. Extrai parâmetros como variáveis locais via extract()
     * 2. Inicia output buffering para capturar conteúdo da view
     * 3. Inclui arquivo da view específica (.php)
     * 4. Captura conteúdo da view com ob_get_clean()
     * 5. Inicia novo buffer para o layout principal
     * 6. Inclui template do layout main.php
     * 7. Substitui placeholder {{content}} pelo conteúdo da view
     * 8. Retorna HTML final combinado
     * 
     * @param string $view Nome da view (sem extensão .php)
     * @param array  $params Array associativo com dados para a view
     * 
     * @return string HTML renderizado com layout aplicado
     * 
     * @example
     * // Renderiza view 'profile/show' com dados do usuário
     * View::render('profile/show', ['user' => $userData, 'posts' => $userPosts]);
     * // Resultado: Layout main.php com conteúdo da view: profile/show.php
     */
    public static function render(string $view, $params = []): string {
        extract($params);

        ob_start();
        include __DIR__ . '/../../view/' . $view . '.php';
        $viewContent = ob_get_clean();

        ob_start();
        include __DIR__ . '/../../view/layouts/main.php';
        $layoutContent = ob_get_clean();

        return str_replace('{{content}}', $viewContent, $layoutContent);
    }

    /**
     * Carrega e renderiza um layout específico
     * 
     * Método utilitário para carregar layouts alternativos além do
     * layout principal. Útil para emails, páginas de erro, ou
     * estruturas de layout diferentes (admin, mobile, etc.)
     * 
     * @param string $layout Nome do layout (sem extensão .php)
     * 
     * @return string Conteúdo HTML do layout renderizado
     * 
     * @throws \Exception Se arquivo de layout não for encontrado
     * 
     * @example
     * // Carrega layout de email
     * $emailLayout = View::layoutContent('email');
     * 
     * // Carrega layout administrativo
     * $adminLayout = View::layoutContent('admin');
     */
    public static function layoutContent(string $layout): string {
        $path = __DIR__ . "/../../view/layouts/{$layout}.php";
        if (!file_exists($path)){
            throw new \Exception("layout não encontrado");
            
        }

        ob_start();
        include_once $path;
        return ob_get_clean();
    }

    /**
     * Renderiza apenas a view sem layout
     * 
     * Renderização simples de view sem aplicação de layout.
     * Útil para componentes, fragmentos HTML, conteúdo AJAX,
     * ou quando se deseja controle total sobre a estrutura final.
     * 
     * Diferenças do render():
     * - Não aplica layout principal
     * - Não usa extract() (implementação manual de variáveis)
     * - Controle direto sobre variáveis disponíveis
     * - Ideal para respostas parciais ou APIs
     * 
     * @param string $view Nome da view (sem extensão .php)
     * @param array  $params Array associativo com dados para a view
     * 
     * @return string HTML da view renderizada (sem layout)
     * 
     * @throws \Exception Se arquivo de view não for encontrado
     * 
     * @example
     * // Renderiza fragmento para AJAX
     * $fragment = View::renderOnlyView('components/user-card', ['user' => $user]);
     * 
     * // Renderiza conteúdo para API
     * $content = View::renderOnlyView('api/user-profile', ['data' => $profileData]);
     */
    public static function renderOnlyView(string $view, $params = []): string {
        $path = __DIR__ . "/../../view/{$view}.php";
        if (!file_exists($path)){
            throw new \Exception("View não encontrada");
        }

        foreach ($params as $key => $value) {
            $$key = $value;
        }
        ob_start();
        include_once $path;
        
        return ob_get_clean();
    }
}