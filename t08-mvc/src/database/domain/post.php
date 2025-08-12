<?php 
declare(strict_types=1);
namespace src\database\domain;

class Post{
    private ?int $id = null;
    private int $userId;
    private string $photoUrl;
    private ?string $uploadDate = null;
    private ?string $description;

    public function __construct(int $userId, string $photoUrl, ?string $description = null) {
        if($userId <= 0) { throw new \InvalidArgumentException('userId deve ser positivo'); }
        $this->userId = $userId;
        $this->setPhotoUrl($photoUrl);
        $this->description = $description;
        $this->setUploadDate(null); 
    }
    
    public function getId(): ?int { return $this->id; }
    public function setId(?int $id): self { 
        if($id !== null && $id <= 0) { 
            throw new \InvalidArgumentException('ID deve ser int positivo'); 
        } 
        $this->id = $id; return $this; 
    }
    
    public function getUserId(): int { return $this->userId; }
    
    public function getPhotoUrl(): string { return $this->photoUrl; }
    public function setPhotoUrl(string $photoUrl): self { 
        if($photoUrl === '') { 
            throw new \InvalidArgumentException('PhotoUrl não pode estar vazio'); 
        } 
        $this->photoUrl= $photoUrl; return $this; 
    }
   
    public function getUploadDate(): ?string { return $this->uploadDate; }
    public function setUploadDate(?string $uploadDate): self { 
        $this->uploadDate = $uploadDate ?? date('Y-m-d H:i:s'); 
        return $this; 
    }

    public function getDescription(): ?string { return $this->description; }
    public function setDescription(?string $description): self { $this->description= $description; return $this; }

}