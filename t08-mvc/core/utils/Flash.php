<?php
namespace Conex\MiniFramework\utils;

/**
 * Class Flash - Sistema de mensagens flash temporárias
 * 
 * Gerencia mensagens temporárias armazenadas em sessão que são exibidas
 * uma única vez ao usuário e automaticamente removidas.
 * Ideal para feedback de ações como sucesso, erro, aviso ou informação
 * 
 * 
 * Funcionalidades principais:
 * - Armazenamento temporário de mensagens em sessão
 * - Categorização por tipo (success, error, warning, info)
 * - Remoção automática após leitura (padrão flash message)
 * - Métodos de conveniência para tipos comuns
 * - Verificação de existência de mensagens
 * 
 * @package Conex\MiniFramework\utils
 * @version 1.0
 */
class Flash {
    
    /**
     * Define uma mensagem flash de um tipo específico
     * 
     * Armazena uma mensagem temporária na sessão sob uma categoria específica.
     * A mensagem permanecerá disponível até ser lida através do método get().
     * Inicia a sessão automaticamente se não estiver ativa.
     * 
     * @param string $type Tipo da mensagem (success, error, warning, info, etc.)
     * @param string $message Conteúdo da mensagem a ser exibida
     * 
     * @return void
     * 
     * @example
     * Flash::set('success', 'Usuário criado com sucesso!');
     * Flash::set('error', 'Falha ao processar pagamento');
     * Flash::set('warning', 'Sessão expirará em 5 minutos');
     * 
     * @see get() Para recuperar mensagens armazenadas
     * @see has() Para verificar existência sem consumir
     */
    public static function set($type, $message) {
        if (!isset($_SESSION)) {
            session_start();
        }
        
        $_SESSION['flash'][$type] = $message;
        error_log("Flash set: $type - $message"); 
    }
    
    /**
     * Recupera e remove mensagens flash da sessão
     * 
     * Obtém mensagens armazenadas e as remove automaticamente da sessão
     * (comportamento padrão de flash messages). Se tipo específico for fornecido,
     * retorna apenas aquela mensagem. Caso contrário, retorna todas as mensagens.
     * 
     * @param string|null $type Tipo específico de mensagem a recuperar.
     *                          Se null, retorna todas as mensagens
     * 
     * @return string|array|null Mensagem específica (string), todas as mensagens (array)
     *                           ou null se não existirem mensagens do tipo especificado
     * 
     * @example
     * // Recupera mensagem específica
     * $error = Flash::get('error');
     * 
     * // Recupera todas as mensagens
     * $allMessages = Flash::get();
     * // Resultado: ['success' => 'Salvo!', 'error' => 'Erro encontrado']
     * 
     * @see set() Para armazenar mensagens
     * @see has() Para verificar existência sem consumir
     */
    public static function get($type = null) {
        if (!isset($_SESSION)) {
            session_start();
        }
        
        if ($type) {
            $message = $_SESSION['flash'][$type] ?? null;
            unset($_SESSION['flash'][$type]);
            return $message;
        }
        
        $messages = $_SESSION['flash'] ?? [];
        unset($_SESSION['flash']);
        error_log("Flash get all: " . print_r($messages, true)); 
        return $messages;
    }
    
    /**
     * Verifica existência de mensagens flash sem consumi-las
     * 
     * Permite verificar se existem mensagens flash armazenadas sem removê-las
     * da sessão. Útil para condicionais em templates ou verificações prévias.
     * 
     * @param string|null $type Tipo específico de mensagem a verificar.
     *                          Se null, verifica se existem mensagens de qualquer tipo
     * 
     * @return bool True se existirem mensagens do tipo especificado ou de qualquer tipo
     * 
     * @example
     * // Verifica se existe mensagem de erro especifica
     * if (Flash::has('error')) {
     *     echo '<div class="alert-error">Existe erro pendente</div>';
     * }
     * 
     * // Verifica se existem mensagens de qualquer tipo
     * if (Flash::has()) {
     *     echo '<div class="flash-container">...</div>';
     * }
     * 
     * @see get() Para recuperar e consumir mensagens
     * @see set() Para armazenar mensagens
     */
    public static function has($type = null) {
        if (!isset($_SESSION)) {
            session_start();
        }
        
        if ($type) {
            return isset($_SESSION['flash'][$type]);
        }
        
        return !empty($_SESSION['flash']);
    }
    
    /**
     * Método específico para mensagens de sucesso
     * 
     * Atalho para Flash::set('success', $message). Usado para feedback
     * positivo de ações como salvamento, criação, atualização bem-sucedida.
     * 
     * @param string $message Mensagem de sucesso a ser exibida
     * 
     * @return void
     * 
     * @example
     * Flash::success('Perfil atualizado com sucesso!');
     * Flash::success('Post publicado!');
     * Flash::success('Email enviado com sucesso');
     * 
     * @see set() Método base para armazenar mensagens
     */
    public static function success($message) {
        self::set('success', $message);
    }
    
    /**
     * Método específico para mensagens de erro
     * 
     * Atalho para Flash::set('error', $message). Usado para feedback
     * negativo de ações como falhas de validação, erros de sistema, operações falhadas.
     * 
     * @param string $message Mensagem de erro a ser exibida
     * 
     * @return void
     * 
     * @example
     * Flash::error('Falha ao salvar dados');
     * Flash::error('Email já está em uso');
     * Flash::error('Acesso negado');
     * 
     * @see set() Método base para armazenar mensagens
     */
    public static function error($message) {
        self::set('error', $message);
    }
    
    /**
     * Método específico para mensagens de aviso
     * 
     * Atalho para Flash::set('warning', $message). Usado para alertas
     * e avisos que requerem atenção do usuário mas não são erros críticos.
     * 
     * @param string $message Mensagem de aviso a ser exibida
     * 
     * @return void
     * 
     * @example
     * Flash::warning('Senha expirará em 3 dias');
     * Flash::warning('Alguns campos opcionais não foram preenchidos');
     * Flash::warning('Conexão instável detectada');
     * 
     * @see set() Método base para armazenar mensagens
     */
    public static function warning($message) {
        self::set('warning', $message);
    }
    
    /**
     * Método específico para mensagens informativas
     * 
     * Atalho para Flash::set('info', $message). Usado para informações
     * gerais, dicas, orientações ou feedback neutro ao usuário.
     * 
     * @param string $message Mensagem informativa a ser exibida
     * 
     * @return void
     * 
     * @example
     * Flash::info('Nova funcionalidade disponível');
     * Flash::info('Dados salvos automaticamente');
     * Flash::info('Verificação de email enviada');
     * 
     * @see set() Método base para armazenar mensagens
     */
    public static function info($message) {
        self::set('info', $message);
    }
}