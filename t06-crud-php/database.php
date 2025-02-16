<?php
/*
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
            $config = require __DIR__ . '/config.php';
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
*/

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
