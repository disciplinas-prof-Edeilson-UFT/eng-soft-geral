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
}