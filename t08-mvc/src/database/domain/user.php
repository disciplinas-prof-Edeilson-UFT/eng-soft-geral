<?php 
declare(strict_types=1);
namespace src\database\domain;

class User{
    private ?int $id = null;
    private string $username;
    private string $email;
    private ?string $passwordHash;
    private ?string $phone;
    private ?string $bio;
    private ?string $profilePicURL;
    private ?int $countFollowers = null;
    private ?int $countFollowing = null;
    private ?string $createdAt = null;

    public function __construct(string $username, string $email, ?string $passwordHash, ?string $phone, ?string $bio= null, ?string $profilePicURL = null) {
        $this->setUsername($username);  
        $this->setEmail($email);
        $this->setPhone($phone);
        $this->passwordHash = $passwordHash;
        $this->bio = $bio;       
        $this->setProfilePicURL($profilePicURL);
    }

    public function getId(): ?int { return $this->id; }

    public function setId(?int $id): self {
        if($id !== null && $id <= 0) { throw new \InvalidArgumentException('ID deve ser int positivo'); }
        $this->id = $id; return $this;
    }

    public function getUsername(): string { return $this->username; }
    public function getCreatedAt(): ?string { return $this->createdAt; }

    public function setCreatedAt(?string $createdAt): self {
        if($createdAt === null) { $this->createdAt = date('Y-m-d H:i:s'); }
        else { $this->createdAt = $createdAt; }
        return $this;
    }

    public function setUsername(string $username): self {
        $username = trim($username);
        if($username === '') { throw new \InvalidArgumentException('Username nao pode estar vazio'); }
        if(mb_strlen($username) < 3) { throw new \InvalidArgumentException('Username deve ter pelo menos 3 caracteres'); }
        $this->username = $username; return $this;
    }

    public function getEmail(): string { return $this->email; }
    public function setEmail(string $email): self {
        $email = trim($email);
        if($email === '') { throw new \InvalidArgumentException('Email nao pode estar vazio'); }
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) { throw new \InvalidArgumentException('formato de email invalido'); }
        $this->email = $email; return $this;
    }

    public function getPasswordHash(): ?string { return $this->passwordHash; }
    public function setPasswordHash(?string $passwordHash): self { $this->passwordHash= $passwordHash; return $this; }

    public function setPassword(string $password, string $confirm_password): self {
        if($password !== $confirm_password) { throw new \InvalidArgumentException('Senhas nao conferem'); }
        if($password === '') { throw new \InvalidArgumentException('Senha nao pode estar vazia'); }
        if(strlen($password) < 8) { throw new \InvalidArgumentException('Senha deve ter no mínimo 8 caracteres'); }
        $this->passwordHash = password_hash($password, PASSWORD_ARGON2I); return $this;
    }

    public function getPhone(): ?string { return $this->phone; }
    public function setPhone(?string $phone): self {
        if($phone === null) { $this->phone = null; return $this; }
        if($phone === '') { throw new \InvalidArgumentException('Telefone nao pode estar vazio'); }
        if(!preg_match('/^(\d{2}|\(\d{2}\))?\s*\d{4,5}[-\s]?\d{4}$/', $phone)) { throw new \InvalidArgumentException('Numero de telefone invalido'); }
        $this->phone= $phone; return $this;
    }

    public function getBio(): ?string { return $this->bio; }
    public function setBio(?string $bio): self { $this->bio = $bio; return $this; }

    public function getProfilePicURL(): ?string { return $this->profilePicURL; }
    public function setProfilePicURL(?string $profilePicURL): self {
        if($profilePicURL !== null && !preg_match('/\.(jpg|jpeg|png|gif)$/i', $profilePicURL)) {
            throw new \InvalidArgumentException('URL de foto de perfil deve terminar com .jpg, .jpeg, .png ou .gif');
        }
        $this->profilePicURL = $profilePicURL; return $this;
    }

    public function getCountFollowers(): ?int { return $this->countFollowers; }
    public function setCountFollowers(?int $countFollowers): self { $this->countFollowers = $countFollowers; return $this; }

    public function getCountFollowing(): ?int { return $this->countFollowing; }
    public function setCountFollowing(?int $countFollowing): self { $this->countFollowing = $countFollowing; return $this; }

}