<?php 

namespace core\mvc;

use Exception;
use Database;
use PDO;
require_once __DIR__ . '/../../database.php';
//contrato para facilitar implementação de outras classses com libs diferentes, por exemplo: PDO e mysqli
// DIP -> facilita criação de testes unitários
interface IModelRepository {
    public function create($table, $data): bool;
    public function find($table, $conditions, $data): array;
    public function delete($table, $conditions): bool;
    public function update($table, $conditions, $data = []): bool;
}

class ModelRepository implements IModelRepository {
    private static PDO $pdo;

    public static function initDb() {
        if (!isset(self::$pdo)) {
            self::$pdo = Database::getInstance()->getConnection();
        }
    }

    private static function convertToArray($data) {
        if(is_array($data)) {
            return $data;
        }

        //se precisar tratar campos específicos do objeto DTO crie nele um metodo "toArray" que será automaticamente chamado aqui
        if (method_exists($data, 'toArray') && is_object($data)) {
            return $data->toArray();
        }

        if (is_object($data)) {
            return get_object_vars($data);
        }

        throw new Exception('parmetro $data deve ser um array ou objeto DTO');
    }
    

    public function create($table, $data): bool {
        self::initDb();

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
    
    //o data pode ser um array ou um objeto DTO, se for vazio não é feita a query com WHERE
    //ex: Model::find('user', [], ['name', 'email']) retorna todos os registros de name e email da tabela user
    //ex: Model::find('user', ['name' => 'abc'], ['name', 'email']) retorna todos os registros da tabela user com os campos name e email para o name 'abc'
    public function find($table, $conditions, $data = ['*']): array {
        self::initDb();

        $conditions = self::convertToArray($conditions);
        
        $keys = array_keys($conditions);
        $sqlWherePlaceholders = []; 

        foreach ($keys as $key) {
            $sqlWherePlaceholders[] = "$key = :$key"; 
        }

        if($data == ['*']){
            $data = '*';
        } else {
            $data = implode(', ', $data);
        }

        $sql = "SELECT $data FROM $table ";
        if (!empty($conditions)) {
            $sql .= " WHERE " . implode(' AND ', $sqlWherePlaceholders);
        }

        $stmt = self::$pdo->prepare($sql);
        
        foreach ($conditions as $key => $value) {
            $stmt->bindValue(":$key", $value);
        }
        
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }

    //ex: Model::update('user', ['name' => 'abc'], ['name' => 'abc', 'email' => 'agc']) atualiza o nome e email para 'def' e 'ghi' respectivamente para todos os registros com nome 'abc'
    public function update($table, $conditions, $data = []): bool {
        self::initDb();

        $data = self::convertToArray($data);
        $conditions = self::convertToArray($conditions);
        
        $sqlSetPlaceholders = []; 
        foreach($data as $key => $value){
            $sqlSetPlaceholders[] = "$key = :set_$key"; 
        }
        
        $sqlWherePlaceholders = []; 
        foreach (array_keys($conditions) as $key) {
            $sqlWherePlaceholders[] = "$key = :where_$key"; 
        }

        $sql = "UPDATE $table";
        
        if (!empty($sqlSetPlaceholders)) {
            $sql .= " SET " . implode(', ', $sqlSetPlaceholders);
            
            if (!empty($sqlWherePlaceholders)) {
                $sql .= " WHERE " . implode(' AND ', $sqlWherePlaceholders);
            }
        }

        $stmt = self::$pdo->prepare($sql);
        
        foreach ($data as $key => $value) {
            $stmt->bindValue(":set_$key", $value);
        }
        foreach ($conditions as $key => $value) {
            $stmt->bindValue(":where_$key", $value);
        }
        
        return $stmt->execute(); 
    }

    //ex: Model::delete('user', ['name' => 'abc']) deleta todos os registros com nome 'abc'
    public function delete($table, $conditions): bool {
        self::initDb();
        
        $conditions = self::convertToArray($conditions);
        
        if (empty($conditions)) {
            throw new Exception('Condições não especificadas para delete');
        }
        
        $sqlWherePlaceholders = []; 
        foreach (array_keys($conditions) as $key) {
            $sqlWherePlaceholders[] = "$key = :$key"; 
        }
        
        $sql = "DELETE FROM $table WHERE " . implode(' AND ', $sqlWherePlaceholders);
        
        $stmt = self::$pdo->prepare($sql);
        
        foreach ($conditions as $key => $value) {
            $stmt->bindValue(":$key", $value);
        }
        
        return $stmt->execute();
    }
    
}