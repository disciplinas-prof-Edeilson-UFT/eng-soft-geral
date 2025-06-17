<?php

namespace src\database;

use PDO;
use Database;

abstract class BaseDAO {
    protected $db;
    
    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }
    
    public function find($table, $id) {
        $stmt= $this->db->prepare("SELECT * FROM {$table} WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    
    public function findAll($table) {
        $stmt= $this->db->prepare("SELECT * FROM {$table}");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function insert($table, $data) {
        $columns= implode(", ", array_keys($data));
        $placeholders = ":" . implode(", :", array_keys($data));
        $stmt = $this->db->prepare("INSERT INTO {$table} ({$columns}) VALUES ({$placeholders})");
        return $stmt->execute($data);
    }

    public function update($table, $data, $id) {
        $set = "";
        foreach ($data as $key => $value) {
            $set .= "{$key} = :{$key}, ";
        }
        $set= rtrim($set, ", ");
        $stmt= $this->db->prepare("UPDATE {$table} SET {$set} WHERE id = :id");
        $data['id'] = $id;
        return $stmt->execute($data);
    }

    public function delete($table, $id) {
        $stmt= $this->db->prepare("DELETE FROM {$table} WHERE id = ?");
        return $stmt->execute([$id]);
    }

    public function executeQuery($query, $params = []) {
        $stmt= $this->db->prepare($query);
        $stmt->execute($params);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}