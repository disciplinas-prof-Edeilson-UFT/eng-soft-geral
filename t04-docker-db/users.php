<?php

class Users{
    private $db;

    public function __construct() {
        $this->db = new Database();
    }
    public function createUser($name, $email, $password){
        $sql = $this->db->connection()->prepare("INSERT INTO users (name, email, password) VALUES ('$name', '$email', '$password')");
        $sql->execute();
    }
}