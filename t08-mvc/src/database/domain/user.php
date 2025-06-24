<?php 
namespace src\database\domain;

class User{
    private $id;
    private $username;
    private $email;
    private $passwordHash;
    private $phone;
    private $bio;
    private $profilePicURL;
    private $countFollowers;
    private $countFollowing;
    private $createdAt;

    public function __construct($username, $email, $passwordHash, $phone, $bio= null, $profilePicURL = null) {
        $this->setUsername($username);  
        $this->setEmail($email);
        $this->setPhone($phone);
        $this->passwordHash = $passwordHash;
        $this->bio = $bio;       
        $this->setProfilePicURL($profilePicURL);
    }

    public function getId(){
        return $this->id;
    }

    public function setId($id) {
        if ($id !== null && !is_numeric($id)) {
            throw new \InvalidArgumentException("ID deve ser int");
        }
        $this->id = $id;
        return $this;
    }

    public function getUsername(){
        return $this->username;
    }

    public function getCreatedAt(){
        return $this->createdAt;
    }

    public function setCreatedAt($createdAt) {
        if (is_numeric($createdAt)) {
            $this->createdAt = date('Y-m-d H:i:s', $createdAt);
        } else if ($createdAt === null) {
            $this->createdAt = date('Y-m-d H:i:s');
        }
        return $this;
    }

    public function setUsername($username) {
        if (empty($username)) {
            throw new \InvalidArgumentException("Username não pode estar vazio");
        }
        $this->username = $username;
        return $this;
    }

    public function getEmail(){
        return $this->email;
    }

    public function setEmail($email) {
        if (empty($email)) {
            throw new \InvalidArgumentException("Email não pode estar vazio");
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new \InvalidArgumentException("formato de email inválido");
        }

        $this->email = $email;
        return $this;
    }

    public function getPasswordHash(){
        return $this->passwordHash;
    }

    public function setPasswordHash($passwordHash) {
        $this->passwordHash= $passwordHash;
        return $this;
    }

    public function setPassword($password, $confirm_password) {
        if ($password !== $confirm_password) {
            throw new \InvalidArgumentException("Senhas não conferem");
        }
        if (empty($password)) {
            throw new \InvalidArgumentException("Senha não pode estar vazia");
        }
        if (strlen($password) < 8) {
            throw new \InvalidArgumentException("Senha deve ter no mínimo 8 caracteres");
        }

        $this->passwordHash = password_hash($password, PASSWORD_ARGON2I);
        return $this;
    }

    public function getPhone(){
        return $this->phone;
    }

    public function setPhone($phone) {
        if (empty($phone)) {
            throw new \InvalidArgumentException("Telefone não pode estar vazio");
        }
        //ex: (99) 99999-9999
        if (!preg_match('/^(\d{2}|\(\d{2}\))?\s*\d{4,5}[-\s]?\d{4}$/', $phone)) {
             throw new \InvalidArgumentException("Numero de telefone inválido");
         }

        $this->phone = $phone;
        return $this;
    }

    public function getBio(){
        return $this->bio;
    }

    public function setBio($bio) {
        $this->bio = $bio;
        return $this;
    }

    public function getProfilePicURL(){
        return $this->profilePicURL;
    }

    public function setProfilePicURL($profilePicURL) {
        if ($profilePicURL !== null && !preg_match('/\.(jpg|jpeg|png|gif)$/i', $profilePicURL)) {
            throw new \InvalidArgumentException("URL de foto de perfil deve terminar com .jpg, .jpeg, .png ou .gif");
        }
        $this->profilePicURL = $profilePicURL;
        return $this;
    }

    public function getCountFollowers(){
        return $this->countFollowers;
    }

    public function setCountFollowers($countFollowers) {
        $this->countFollowers = $countFollowers;
        return $this;
    }

    public function getCountFollowing(){
        return $this->countFollowing;
    }

    public function setCountFollowing($countFollowing) {
        $this->countFollowing = $countFollowing;
        return $this;
    }

    public function toArray(): array {
        $data = [
            'username' => $this->username,
            'email' => $this->email,
            'password_hash' => $this->passwordHash,      
            'phone' => $this->phone,
            'bio' => $this->bio,
            'profile_pic_url' => $this->profilePicURL,  
        ];
        
        if ($this->id !== null) {
            $data['id'] = $this->id;
        }
        
        if ($this->countFollowers !== null) {
            $data['count_followers'] = $this->countFollowers;
        }
        
        if ($this->countFollowing !== null) {
            $data['count_following'] = $this->countFollowing;
        }
        
        if ($this->createdAt !== null) {
            $data['created_at'] = $this->createdAt;
        }
        
        return $data;
    }
}