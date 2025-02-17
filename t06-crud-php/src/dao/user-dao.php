<?php
class UserDao
{
    private $db;

    public function __construct(Database $db)
    {
        $this->db = $db->getConnection();
    }

    public function createUser($name, $email, $password, $phone)
    {
        $sql = "INSERT INTO users (name, email, password, phone) VALUES (:name, :email, :password, :phone)";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            ':name' => $name,
            ':email' => $email,
            ':password' => $password,
            ':phone' => $phone
        ]);
    }

    public function checkEmailExists($email)
    {
        $sql = "SELECT id FROM users WHERE email = :email";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':email' => $email]);
        return $stmt->fetch() ? true : false;
    }

    public function getUserByEmail($email)
    {
        $sql = "SELECT id, name, email, password, phone FROM users WHERE email = :email";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':email' => $email]);
        return $stmt->fetch();
    }
}


    /*public function updateUser($name, $email, $bio, $phone, $id): bool {
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
        */
