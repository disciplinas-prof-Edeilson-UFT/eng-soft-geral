<?php

class Database{

    public function connection(){
        try {
            $db = new PDO("pgsql:host=localhost;port=5433;dbname=postgres;user=admin;password=root");
            $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        
        } catch (PDOException $e) {
            echo "Falha ao conectar com o banco: " . $e->getMessage() . "\n";
        }
        return $db;
    }

}

?>