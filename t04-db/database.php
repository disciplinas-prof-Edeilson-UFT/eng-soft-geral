<?php
class Database {
    private static $instance = null;
    private $connection;

    private function __construct() {
        $config = require __DIR__ . '/config.php';
        
        $dbConfig = $this->detect_available_database($config['database']);
        
        try {
            $this->connection = new PDO($dbConfig['dsn'], $dbConfig['username'], $dbConfig['password'], [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
            ]);

            if (strpos($dbConfig['dsn'], 'pgsql') !== false) {
                $this->connection->exec("SET search_path TO conex, public");
            } else if (strpos($dbConfig['dsn'], 'mysql') !== false) {
                $this->connection->exec("SET NAMES 'utf8mb4'");
            }
        } catch (PDOException $e) {
            die("Falha ao conectar com o banco: " . $e->getMessage());
        }
    }
    
    private function detect_available_database($configs) {
        if ($this->test_connection($configs['postgresql'], 'postgresql')) {
            return [
                'dsn' => "pgsql:host={$configs['postgresql']['host']};port={$configs['postgresql']['port']};dbname={$configs['postgresql']['dbname']}",
                'username' => $configs['postgresql']['username'],
                'password' => $configs['postgresql']['password']
            ];
        }
        
        if ($this->test_connection($configs['mysql'], 'mysql')) {
            return [
                'dsn' => "mysql:host={$configs['mysql']['host']};port={$configs['mysql']['port']};dbname={$configs['mysql']['dbname']};charset=utf8mb4",
                'username' => $configs['mysql']['username'],
                'password' => $configs['mysql']['password']
            ];
        }
        
        throw new Exception("Nenhum banco de dados disponível");
    }
    
    private function test_connection($config, $type) {
        try {
            if ($type === 'postgresql') {
                $dsn = "pgsql:host={$config['host']};port={$config['port']};dbname={$config['dbname']}";
            } else {
                $dsn = "mysql:host={$config['host']};port={$config['port']};dbname={$config['dbname']}";
            }
            
            $test = new PDO($dsn, $config['username'], $config['password']);
            $test = null; 
            return true;
        } catch (PDOException $e) {
            return false;
        }
    }
    
    public static function get_instance() {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function get_connection() {
        return $this->connection;
    }
}