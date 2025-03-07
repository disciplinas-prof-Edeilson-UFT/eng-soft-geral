<?php
class Database
{
    private static $instance = null;
    private $conn;

    private function __construct()
    {
        $config = require __DIR__ . '/config.php';

        if (!isset($config['database']['host'], $config['database']['port'], $config['database']['username'], $config['database']['password'], $config['database']['dbname'])) {
            throw new Exception("Configuração do banco de dados incompleta");
        }

        $dsn = "mysql:host={$config['database']['host']};port={$config['database']['port']};dbname={$config['database']['dbname']};charset=utf8mb4";

        try {
            $this->conn = new PDO($dsn, $config['database']['username'], $config['database']['password'], [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
            ]);
        } catch (PDOException $e) {
            throw new Exception("Falha na conexão com o banco de dados: " . $e->getMessage());
        }
    }

    public static function getInstance()
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function getConnection()
    {
        return $this->conn;
    }
}


/*
#Conexão com o banco postgres no contatiner docker:
class Database {
    private static $instance = null;
    private $connection;

    public function __construct($host, $port, $username, $password, $dbname) {
        try {
            $this->connection = new PDO("pgsql:host=$host;port=$port;dbname=$dbname", $username, $password);
            $this->connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            die("Falha ao conectar com o banco: " . $e->getMessage());
        }
    }
    
    public static function getInstance() {
        if (self::$instance === null) {
            $config = require __DIR__ . '/config.php';
            self::$instance = new self(
                $config['database']['host'],
                $config['database']['port'],
                $config['database']['username'],
                $config['database']['password'],
                $config['database']['dbname']
            );
        }
        return self::$instance;
    }

    public function getConnection(){
         return $this->connection;
    }
}
*/
