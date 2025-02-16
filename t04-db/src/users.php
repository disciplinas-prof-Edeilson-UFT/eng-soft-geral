<?php
class Users {
    private $db;

    public function __construct(Database $db) {
        $this->db = $db;
    }

    public function createUser($name, $email, $password, $phone) {
        $conn = $this->db->getConnection();

        $sql = "INSERT INTO users (name, email, password_hash, phone) VALUES (:name, :email, :password, :phone)";
        $stmt = $conn->prepare($sql);

        if ($stmt === false) {
            throw new Exception("Erro na preparação da query: " . $conn->error);
        }

        $result = $stmt->execute([':name' => $name,':email' => $email,':password'=> $password,':phone' => $phone]);
        
        return $result;
    }

    public function checkEmailExists($email): bool {
        $conn = $this->db->getConnection();

        $sql = "SELECT * FROM users WHERE email = :email";
        $stmt = $conn->prepare($sql);

        if ($stmt === false) {
            throw new Exception("Erro na preparação da query: " . $conn->error);
        }

        $stmt->execute([':email' => $email]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        return $result ? true : false;
    }

    public function getUserById($id) {
        $conn = $this->db->getConnection();

        $sql = "SELECT * FROM users WHERE id = ?";
        $stmt = $conn->prepare($sql);

        if ($stmt === false) {
            throw new Exception("Erro na preparação da query: " . $conn->error);
        }

        $stmt->execute([':id' => $id]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);


        return $result;
    }
}