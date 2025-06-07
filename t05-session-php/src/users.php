<?php
class Users
{
    private $db;

    public function __construct(Database $db)
    {
        $this->db = $db->get_connection();
    }

    public function create_user($username, $email, $password, $phone)
    {
        try{
            $sql = "INSERT INTO users (username, email, password_hash, phone) VALUES (:username, :email, :password, :phone)";
            $stmt = $this->db->prepare($sql);
            return $stmt->execute([':username' => $username, ':email' => $email, ':password' => $password, ':phone' => $phone]);
        }catch (PDOException $e) {
            throw new Exception("Falha ao inserir usuário no banco: " . $e->getMessage());
        }
    }

    public function check_email_exists($email)
    {
        try{
            $sql = "SELECT id FROM users WHERE email = :email";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([':email' => $email]);
            return $stmt->fetch() ? true : false;
        }catch (PDOException $e) {
            throw new Exception("Falha ao verificar email: " . $e->getMessage());
        }
        
    }

    public function get_user_by_email($email)
    {
        try{
            $sql = "SELECT id, username, email, password_hash, phone FROM users WHERE email = :email";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([':email' => $email]);
            return $stmt->fetch();
        } catch (Exception $e) {
            throw new Exception("Erro ao validar email: " . $e->getMessage());
        }   
        
    }
}