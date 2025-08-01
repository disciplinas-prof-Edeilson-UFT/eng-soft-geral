<?php
namespace src\database\domain;

class Follow {
    private ?int $id = null;
    private int $followerId;
    private int $followingId;
    private ?string $createdAt = null;

    public function __construct(?int $followerId = null, ?int $followingId = null, ?string $createdAt = null, ?int $id = null) {
        $this->followerId = $followerId;
        $this->followingId = $followingId;
        $this->createdAt = $createdAt;
        $this->id = $id;

    }

    public function getId(): ?int {
        return $this->id;
    }

    public function getFollowerId(): int {
        return $this->followerId;
    }

    public function getFollowingId(): int {
        return $this->followingId;
    }

    public function getCreatedAt(): ?string {
        return $this->createdAt;
    }

    public function setId(int $id): void {
        $this->id = $id;
    }

    public function setFollowerId(int $followerId): void {
        $this->followerId = $followerId;
    }

    public function setFollowingId(int $followingId): void {
        $this->followingId = $followingId;
    }

    public function setCreatedAt(string $createdAt): void {
        $this->createdAt = $createdAt;
    }

    public function toArray(): array {
        $array = [
            'follower_id' => $this->followerId,
            'following_id' => $this->followingId
        ];
        
        if ($this->id !== null) {
            $array['id'] = $this->id;
        }
        
        if ($this->createdAt !== null) {
            $array['created_at'] = $this->createdAt;
        }
        
        return $array;
    }
}