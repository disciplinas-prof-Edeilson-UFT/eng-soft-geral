<?php

class UserRepository{

    private $db;

    public function __construct() {
        $this->db = new Database();
    }

    public function updateUser($name, $email, $bio, $phone, $id){
        $sql = $this->$db->connection()->prepare("UPDATE users SET name='$name', email='$email', bio='$bio', phone='$phone' WHERE id='$id'");
        $sql->execute();
    }

    public function getUser($id){
        $sql = $this->$db->connection()->prepare("SELECT * FROM users WHERE id='$id'");
        $sql->execute();
        return $sql->fetch();
    }

    public function deleteUser($id){
        $sql = $this->$db->connection()->prepare("DELETE FROM users WHERE id='$id'");
        if ($sql === false) {
            throw new Exception("Erro na preparação da query: " . $conn->error);
        }
        $sql->execute();
    }

    public function getAllUsers(){
        $sql = $this->$db->connection()->prepare("SELECT * FROM users");
        $sql->execute();
        return $sql->fetchAll();
    }

    public function createUser($name, $email, $bio, $phone){
        $sql = $this->$db->connection()->prepare("INSERT INTO users (name, email, bio, phone) VALUES ('$name', '$email', '$bio', '$phone')");
        $sql->execute();
    }
}