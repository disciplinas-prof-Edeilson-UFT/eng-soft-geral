<?php 
namespace src\database\domain;
use DateTime;

class User{
    public $id;
    public $username;
    public $email;
    public $password_hash;
    public $phone;
    public $bio;
    public $profile_pic_url;
    public $confirm_password;

    public function __construct($username, $email, $password_hash, $confirm_password, $phone, $bio = null, $profile_pic_url = null) {
        $this->username = $username;
        $this->email = $email;
        $this->password_hash = $password_hash;
        $this->phone = $phone;
        $this->bio = $bio;
        $this->profile_pic_url = $profile_pic_url;
        $this->confirm_password = $confirm_password;

    }
    public function getId(){
        return $this->id;
    }

    public function getUsername(){
        return $this->username;
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
        return $this->password_hash;
    }

    public function setPasswordHash($password, $confirm_password) {
        if ($password !== $confirm_password) {
            throw new \InvalidArgumentException("Senhas não conferem");
        }

        if (empty($password)) {
            throw new \InvalidArgumentException("Senha não pode estar vazia");
        }

        if (strlen($password) < 8) {
            throw new \InvalidArgumentException("Senha deve ter no mínimo 8 caracteres");
        }

        $this->password_hash = password_hash($password, PASSWORD_DEFAULT);
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

    public function getProfilePicUrl(){
        return $this->profile_pic_url;
    }

    public function setProfilePicUrl($profile_pic_url) {
        $this->profile_pic_url = $profile_pic_url;
        return $this;
    }

    public function toArray() {
        $data = [
            'username' => $this->username,
            'email' => $this->email,
            'password_hash' => $this->password_hash,
            'phone' => $this->phone,
            'bio' => $this->bio,
            'profile_pic_url' => $this->profile_pic_url,
        ];
        if ($this->id !== null) {
            $data['id'] = $this->id;
        }
        return $data;
    }
}