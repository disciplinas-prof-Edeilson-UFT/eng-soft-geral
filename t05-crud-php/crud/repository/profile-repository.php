<?php

class ProfileRepository{

    public function updateProfile($db, $name, $email, $bio, $phone, $id){
        $sql = $db->connection()->prepare("UPDATE users SET name='$name', email='$email', bio='$bio', phone='$phone' WHERE id='$id'");
        $sql->execute();
    }

    public function getProfile($db, $id){
        $sql = $db->connection()->prepare("SELECT * FROM users WHERE id='$id'");
        $sql->execute();
        return $sql->fetch();
    }

    public function deleteProfile($db, $id){
        $sql = $db->connection()->prepare("DELETE FROM users WHERE id='$id'");
        $sql->execute();
    }

    public function getAll($db){
        $sql = $db->connection()->prepare("SELECT * FROM users");
        $sql->execute();
        return $sql->fetchAll();
    }

    public function updateBio($db, $bio, $id){
        $sql = $db->connection()->prepare("UPDATE users SET bio='$bio' WHERE id='$id'");
        $sql->execute();
    }
}