<?php
namespace Conex\MiniFramework\utils;

/**
 * Class Session - Gerenciador de sessões PHP
 * 
 * Fornece uma interface reutilizavel para operações de sessão PHP,
 * encapsulando a funcionalidade nativa de sessões em métodos estáticos
 * de fácil uso e com verificações de segurança integradas
 * 
 * A classe gerencia o estado da sessão, iniciando-a
 * apenas quando necessário e fornecendo métodos para operações comuns
 * como armazenar, recuperar, verificar e remover dados da sessão
 * 
 * Funcionalidades principais:
 * - Início automático e inteligente de sessões
 * - Armazenamento e recuperação de dados com valores padrão
 * - Verificação de existência de chaves
 * - Remoção seletiva e destruição completa de sessão
 * - Interface segura sobre funcionalidades nativas do PHP
 * 
 * @package Conex\MiniFramework\utils
 * @version 1.0
 */
class Session{
    
    /**
     * Inicia a sessão PHP de forma segura
     * 
     * Verifica o status atual da sessão e inicia apenas se não estiver
     * ativa. Previne erros de múltiplas chamadas session_start() e
     * garante que a sessão esteja disponível para uso.
     * 
     * Utiliza session_status() para verificar se a sessão já está ativa,
     * evitando warnings e comportamentos inesperados.
     * 
     * @return void
     * 
     * @example
     * // Inicia sessão explicitamente (raramente necessário)
     * Session::start();
     * 
     * @see get() Inicia sessão automaticamente ao recuperar dados
     * @see set() Inicia sessão automaticamente ao armazenar dados
     * @see session_status() Função PHP nativa para verificar status da sessão
     * 
     * @throws void Não gera exceções, mas pode gerar warnings PHP se configuração estiver incorreta
     */
    public static function start(): void {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }
    
    /**
     * Recupera valor armazenado na sessão com suporte a valor padrão
     * 
     * Obtém um valor da sessão usando uma chave específica. Se a chave
     * não existir, retorna o valor padrão especificado. Inicia a sessão
     * automaticamente se necessário.
     * 
     * @param string $key Chave do valor a ser recuperado da sessão
     * @param mixed $default Valor padrão retornado se a chave não existir.
     *                       Padrão: null
     * 
     * @return mixed Valor armazenado na sessão ou valor padrão
     * 
     * @example
     * // Recupera ID do usuário ou null se não logado
     * $userId = Session::get('user_id');
     * 
     * // Recupera configuração com valor padrão
     * $theme = Session::get('theme', 'light');
     * 
     * // Recupera dados complexos
     * $userData = Session::get('user_data', []);
     * 
     * @see set() Para armazenar valores na sessão
     * @see has() Para verificar existência sem recuperar valor
     */
    public static function get($key, $default = null) {
        self::start();
        return $_SESSION[$key] ?? $default;
    }

    /**
     * Armazena valor na sessão sob uma chave específica
     * 
     * Define um valor na sessão global usando a chave fornecida.
     * Suporta qualquer tipo de dados PHP (string, array, object, etc.).
     * Inicia a sessão automaticamente se necessário.
     * 
     * @param string $key Chave sob a qual o valor será armazenado
     * @param mixed $value Valor a ser armazenado (qualquer tipo PHP)
     * @return void
     * 
     * @example
     * // Armazena ID do usuário após login
     * Session::set('user_id', 123);
     * 
     * // Armazena dados complexos
     * Session::set('user_data', [
     *     'name' => 'João',
     *     'email' => 'joao@email.com',
     *     'role' => 'admin'
     * ]);
     * 
     * // Armazena configurações
     * Session::set('preferences', $userPreferences);
     * 
     * @see get() Para recuperar valores armazenados
     * @see remove() Para remover valores específicos
     */
    public static function set($key, $value): void {
        self::start();
        $_SESSION[$key] = $value;
    }
    
    /**
     * Remove valor específico da sessão
     * 
     * Exclui uma chave e seu valor associado da sessão atual.
     * Não afeta outros dados da sessão. Inicia a sessão automaticamente
     * se necessário.
     * 
     * @param string $key Chave do valor a ser removido da sessão
     * @return void
     * 
     * @example
     * // Remove dados do usuário após logout
     * Session::remove('user_id');
     * Session::remove('user_data');
     * 
     * // Remove configuração temporária
     * Session::remove('temp_upload_id');
     * 
     * @see set() Para armazenar valores
     * @see destroy() Para remover toda a sessão
     * @see has() Para verificar existência antes da remoção
     */
    public static function remove($key): void {
        self::start();
        unset($_SESSION[$key]);
    }

    /**
     * Verifica se uma chave existe na sessão
     * 
     * Determina se uma chave específica foi definida na sessão atual,
     * independentemente do seu valor (pode ser null). Útil para
     * verificações condicionais sem recuperar o valor.
     * 
     * @param string $key Chave a ser verificada na sessão
     * @return bool True se a chave existir na sessão, false caso contrário
     * 
     * @example
     * // Verifica se usuário está logado
     * if (Session::has('user_id')) {
     *     // Mostrar painel do usuário
     * } else {
     *     // Redirecionar para login
     * }
     * 
     * // Verifica configuração específica
     * if (Session::has('admin_mode')) {
     *     // Mostrar opções administrativas
     * }
     * 
     * @see get() Para recuperar valor se existir
     * @see set() Para definir chaves na sessão
     */
    public static function has($key): bool {
        self::start();
        return isset($_SESSION[$key]);
    }
    
    /**
     * Destroi completamente a sessão atual
     * 
     * Remove todos os dados da sessão e invalida o ID da sessão.
     * Normalmente usado durante logout ou quando se deseja limpar
     * completamente o estado da sessão do usuário.
     * 
     * Esta operação é irreversível e remove TODOS os dados armazenados
     * na sessão atual. Uma nova sessão será criada na próxima operação
     * que requer sessão.
     * 
     * @return void
     * 
     * @example
     * // Logout completo do usuário
     * Session::destroy();
     * 
     * // Limpeza de sessão após operação crítica
     * if ($securityBreach) {
     *     Session::destroy();
     * }
     * 
     * @see remove() Para remoção seletiva de dados
     * @see session_destroy() Função PHP nativa utilizada internamente
     * 
     * @warning Esta operação remove TODOS os dados da sessão e não pode ser desfeita
     */
    public static function destroy(): void {
        self::start();
        session_destroy();
    }
}