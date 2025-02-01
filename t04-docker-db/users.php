<?php

class Users{
    private $db;

    public function __construct() {
        $this->db = new Database();
    }
    public function createUser($name, $email, $password, $phone){
        $sql = $this->db->connection()->prepare("INSERT INTO users (name, email, password, phone) VALUES ('$name', '$email', '$password', '$phone')");
        $sql->execute();
    }
}