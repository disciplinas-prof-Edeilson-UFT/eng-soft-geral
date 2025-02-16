<?php
class UserDao {

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

    
    public function getUserByEmail($email)
    {
        $conn = $this->db->getConnection();

        $sql = "SELECT id, name, email, password_hash, phone FROM users WHERE email = :email";
        $stmt = $conn->prepare($sql);

        if ($stmt === false) {
            throw new Exception("Erro na preparação da query: " . $conn->error);
        }

        $stmt->execute([':email' => $email]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        return $result;
    }

    public function updateUser($name, $email, $bio, $phone, $id): bool {
        $conn = $this->db->getConnection();

        $sql = "UPDATE users SET name = :name, email = :email, bio = :bio, phone = :phone WHERE id = :id";
        $stmt = $conn->prepare($sql);

        if ($stmt === false) {
            throw new Exception("Erro na preparação da query: " . $conn->error);
        }

        $stmt = execute([":name" => $name, ":email" => $email, ":bio" => $bio, ':phone' => $phone, ":id" => $id]);

        if (!$stmt->execute()) {
            throw new Exception("Erro ao executar a query: " . $stmt->error);
        }

        return true;
    }

    public function deleteUser($id) {
        $conn = $this->db->getConnection();

        $sql = "DELETE FROM users WHERE id = ?";
        $stmt = $conn->prepare($sql);

        if ($stmt === false) {
            throw new Exception("Erro na preparação da query: " . $conn->error);
        }

        $stmt->execute([':id' => $id]);

        if (!$stmt->execute()){
            throw new Exception("Erro ao executar a query: " . $stmt->error);
        }

        $stmt->close();
        return true;
    }
}