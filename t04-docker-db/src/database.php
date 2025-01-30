<?php

class Database {
    private $host = 'mysql_8.1.0_container';  
    private $dbname = 'example'; 
    private $username = 'root'; 
    private $password = 'example'; 
    private $pdo;

    public function __construct() {
        $this->connect();
    }

    private function connect() {
        try {
            $this->pdo = new PDO(
                "mysql:host={$this->host};dbname={$this->dbname}",
                $this->username,
                $this->password,
                array(PDO::ATTR_TIMEOUT => 5) 
            );
            $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ATTR_ERRMODE_EXCEPTION);
            echo "Connected successfully\n";
        } catch (PDOException $e) {
            echo "Connection failed: " . $e->getMessage() . "\n";
            $this->pdo = null;
        }
    }
    
    public function createDatabase() {
        if (!$this->pdo) {
            echo "No database connection available\n";
            return;
        }
    
        $usersTable = "CREATE TABLE IF NOT EXISTS users (
            id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            name VARCHAR(30) NOT NULL,
            email VARCHAR(50) NOT NULL,
            reg_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
        )";
    
        try {
            $this->pdo->exec($usersTable);
            echo "Table 'users' created successfully\n";
        } catch (PDOException $e) {
            echo "Table creation failed: " . $e->getMessage() . "\n";
        }
    }
}

$database = new Database();
$database->createDatabase();
?>