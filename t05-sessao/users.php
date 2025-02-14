<?php
class Users
{
    private $db;

    public function __construct(Database $db)
    {
        $this->db = $db;
    }

    public function createUser($name, $email, $password, $phone)
    {
        $conn = $this->db->getConnection();

        $sql = "INSERT INTO users (name, email, password, phone) VALUES (?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);

        if ($stmt === false) {
            throw new Exception("Erro na preparação da query: " . $conn->error);
        }

        $stmt->bind_param("ssss", $name, $email, $password, $phone);

        if (!$stmt->execute()) {
            throw new Exception("Erro ao executar a query: " . $stmt->error);
        }

        $stmt->close();
        return true;
    }

    public function checkEmailExists($email)
    {
        $conn = $this->db->getConnection();

        $sql = "SELECT id FROM users WHERE email = ?";
        $stmt = $conn->prepare($sql);

        if ($stmt === false) {
            throw new Exception("Erro na preparação da query: " . $conn->error);
        }

        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->store_result();
        $num_rows = $stmt->num_rows;

        $stmt->close();

        return $num_rows > 0;
    }

    public function getUserById($id)
    {
        $conn = $this->db->getConnection();

        $sql = "SELECT id, name, email, phone FROM users WHERE id = ?";
        $stmt = $conn->prepare($sql);

        if ($stmt === false) {
            throw new Exception("Erro na preparação da query: " . $conn->error);
        }

        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        $user = $result->fetch_assoc();

        $stmt->close();

        return $user;
    }

    public function updateUser($id, $name, $email, $phone)
    {
        $conn = $this->db->getConnection();

        $sql = "UPDATE users SET name = ?, email = ?, phone = ? WHERE id = ?";
        $stmt = $conn->prepare($sql);

        if ($stmt === false) {
            throw new Exception("Erro na preparação da query: " . $conn->error);
        }

        $stmt->bind_param("sssi", $name, $email, $phone, $id);

        $result = $stmt->execute();

        $stmt->close();

        return $result;
    }

    public function deleteUser($id)
    {
        $conn = $this->db->getConnection();

        $sql = "DELETE FROM users WHERE id = ?";
        $stmt = $conn->prepare($sql);

        if ($stmt === false) {
            throw new Exception("Erro na preparação da query: " . $conn->error);
        }

        $stmt->bind_param("i", $id);

        $result = $stmt->execute();

        $stmt->close();

        return $result;
    }

    public function getUserByEmail($email)
    {
        $conn = $this->db->getConnection();

        $sql = "SELECT id, name, email, password, phone FROM users WHERE email = ?";
        $stmt = $conn->prepare($sql);

        if ($stmt === false) {
            throw new Exception("Erro na preparação da query: " . $conn->error);
        }

        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();
        $user = $result->fetch_assoc();

        $stmt->close();

        return $user;
    }
}