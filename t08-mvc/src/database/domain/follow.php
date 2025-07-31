<?php 
namespace src\database\domain;

class Follow {
    private $id;
    private $follower_id;    
    private $following_id;  
    private $created_at;

    public function __construct($follower_id, $following_id) {
        $this->follower_id = $follower_id;
        $this->following_id = $following_id;
        $this->created_at = date('Y-m-d H:i:s');
    }

    public function getId() { return $this->id; }
    public function setId($id) { $this->id = $id; return $this; }
    
    public function getFollowerId() { return $this->follower_id; }
    public function getFollowingId() { return $this->following_id; }
    
    public function getCreatedAt() { return $this->created_at; }
    public function setCreatedAt($created_at) { $this->created_at = $created_at; return $this; }

    public function toArray(): array {
        return [
            'follower_id' => $this->follower_id,
            'following_id' => $this->following_id,
            'created_at' => $this->created_at
        ];
    }
}