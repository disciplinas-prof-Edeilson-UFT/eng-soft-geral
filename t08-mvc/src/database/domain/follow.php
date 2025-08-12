<?php
declare(strict_types=1);
namespace src\database\domain;

class Follow {
    private ?int $id = null;
    private int $followerId;
    private int $followingId;
    private ?string $createdAt = null;

    public function __construct(?int $followerId = null, ?int $followingId = null, ?string $createdAt = null, ?int $id = null) {
        if($followerId !== null && $followingId !== null) {
            $this->assertIds($followerId, $followingId);
            $this->followerId = $followerId; 
            $this->followingId = $followingId; 
        } elseif ($followerId !== null) { $this->setFollowerId($followerId); }
        elseif ($followingId !== null) { $this->setFollowingId($followingId); }
        $this->createdAt = $createdAt ?? date('Y-m-d H:i:s'); 
        $this->id = $id;
    }

    public function getId(): ?int { return $this->id; }
    public function setId(int $id): void { $this->id = $id; }

    public function getFollowerId(): int { return $this->followerId; }
    public function setFollowerId(int $followerId): void { 
        if($followerId <= 0) { throw new \InvalidArgumentException('followerId deve ser positivo'); }
        if(isset($this->followingId) && $this->followingId === $followerId) { throw new \InvalidArgumentException('user não pode seguir a si mesmo'); }
        $this->followerId = $followerId; 
    }

    public function getFollowingId(): int { return $this->followingId; }
    public function setFollowingId(int $followingId): void { 
        if($followingId <= 0) { throw new \InvalidArgumentException('followingId deve ser positivo'); }
        if(isset($this->followerId) && $this->followerId === $followingId) { throw new \InvalidArgumentException('user não pode seguir a si mesmo'); }
        $this->followingId = $followingId; 
    }

    public function getCreatedAt(): ?string { return $this->createdAt; }
    public function setCreatedAt(string $createdAt): void { $this->createdAt = $createdAt; }

    private function assertIds(int $followerId, int $followingId): void {
        if($followerId <= 0 || $followingId <= 0) { throw new \InvalidArgumentException('IDs devem ser positivos');}
        if($followerId === $followingId) { throw new \InvalidArgumentException('user nao pode seguir a si mesmo');} 
    }

}