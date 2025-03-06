<?php 

namespace core\mvc;

use Exception;
use Database;
use PDO;


class Model {
    private static PDO $pdo;

    public static function initDb() {
        if (!isset(self::$pdo)) {
            self::$pdo = Database::getInstance()->getConnection();
        }
    }

    private static function convertToArray($data) {
        self::initDb();

        if(is_array($data)) {
            return $data;
        }

        if (method_exists($data, 'toArray') && is_object($data)) {
            return $data->toArray();
        }

        if (is_object($data)) {
            return get_object_vars($data);
        }

        throw new Exception('parmetro $data deve ser um array ou objeto DTO');
    }
    
    public static function create($table, $data) {
        $data = self::convertToArray($data);
        
        $keys = array_keys($data);
        $sqlPlaceholders = []; 

        foreach ($keys as $key) {
            $sqlPlaceholders[] = ":$key"; 
        }
        
        $sql = "INSERT INTO $table (" . implode(', ', $keys) . ") VALUES (" . implode(', ', $sqlPlaceholders) . ")";
        $stmt = self::$pdo->prepare($sql);
        
        foreach ($data as $key => $value) {
            $stmt->bindValue(":$key", $value);
        }
        
        return $stmt->execute();
    }
    

    public static function findAll($table, $data) {
        $data = self::convertToArray($data);
        
        $keys = array_keys($data);
        $sqlWherePlaceholders = []; 

        foreach ($keys as $key) {
            $sqlWherePlaceholders[] = "$key = :$key"; 
        }

        $sql = "SELECT * FROM $table ";
        if (!empty($data)) {
            $sql = "SELECT * FROM $table WHERE " . implode(' AND ', $sqlWherePlaceholders);
        }

        $stmt = self::$pdo->prepare($sql);
        
        foreach ($data as $key => $value) {
            $stmt->bindValue(":$key", $value);
        }
        
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }

    public static function update($table, $data){
    
    }

    public static function delete($table, $data){}

    public static function findOne($table, $data){}
    public static function findByAttributes($table, $data){

    }

}