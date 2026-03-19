<?php
declare(strict_types=1);

namespace src\database;

use PDO;
use Database;

abstract class BaseDAO {

    protected PDO $db;
    
    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }
    
    public function find(string $table, int $id): ?array {
        $stmt= $this->db->prepare("SELECT * FROM {$table} WHERE id = ?"); 
        $stmt->execute([$id]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row === false ? null : $row;
    }
    
    public function findAll(string $table): array {
        $stmt= $this->db->prepare("SELECT * FROM {$table}");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function insert(string $table, array $data): bool {
        $columns= implode(", ", array_keys($data));
        $placeholders= ":" . implode(", :", array_keys($data)); 
        $stmt= $this->db->prepare("INSERT INTO {$table} ({$columns}) VALUES ({$placeholders})");
        return $stmt->execute($data);
    }

    public function update(string $table, array $data, int $id): bool {
        $set = "";
        foreach ($data as $key => $value) {
            $set .= "{$key} = :{$key}, ";
        }
        $set= rtrim($set, ", ");
        $stmt= $this->db->prepare("UPDATE {$table} SET {$set} WHERE id = :id");
        $data['id'] = $id;
        return $stmt->execute($data);
    }

    public function delete(string $table, int $id): bool {
        $stmt= $this->db->prepare("DELETE FROM {$table} WHERE id = ?");
        return $stmt->execute([$id]);
    }

    public function executeQuery(string $query, array $params = []): array {
        $stmt = $this->db->prepare($query);
        $stmt->execute($params);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function beginTransaction(): void { 
        $this->db->beginTransaction(); 
    }
    public function commit(): void { $this->db->commit(); }
    public function rollback(): void { $this->db->rollBack(); }
}