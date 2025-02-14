<?php
class Database {
    private static $instance = null;
    private $servername;
    private $username;
    private $password;
    private $dbname;
    private $conn;

    private function __construct($servername, $username, $password, $dbname) {
        $this->servername = $servername;
        $this->username = $username;
        $this->password = $password;
        $this->dbname = $dbname;

        $this->conn = new mysqli($this->servername, $this->username, $this->password, $this->dbname);

        if ($this->conn->connect_error) {
            throw new Exception("Conexão falhou: " . $this->conn->connect_error);
        }
    }

    public static function getInstance() {
        if (self::$instance === null) {
            $config = require __DIR__ . '/config/config.php';
            self::$instance = new self(
                $config['database']['servername'],
                $config['database']['username'],
                $config['database']['password'],
                $config['database']['dbname']
            );
        }
        return self::$instance;
    }

    public function getConnection() {
        return $this->conn;
    }

    public function closeConnection() {
        $this->conn->close();
    }
}

/*

conexao postgrez docker 

<?php

class Database {
    private $connection;
    
    public function __construct() {
        try {
            $this->connection = new PDO("pgsql:host=localhost;port=5432;dbname=postgres", "admin", "root");
            $this->connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            die("Falha ao conectar com o banco: " . $e->getMessage());
        }
    }
    
    public function connection(){
         return $this->connection;
    }
}*/