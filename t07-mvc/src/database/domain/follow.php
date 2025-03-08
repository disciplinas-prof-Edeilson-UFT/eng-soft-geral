<?php 

namespace src\database\domain;

class Follow{
    private $id;
    private $follower_id;
    private $user_id;
    private $created_at;

    public function __construct($follower_id, $user_id) {
        $this->follower_id = $follower_id;
        $this->user_id = $user_id;
    }
    public function getUserId() {
        return $this->user_id;
    }

    public function setUserId($user_id) {
        if (empty($user_id)) {
            throw new \InvalidArgumentException("User ID não pode estar vazio");
        }
        $this->user_id = $user_id;
        return $this;
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

    public function getFollowerId(){
        return $this->follower_id;
    }

    public function setFollowerId($follower_id) {
        if (empty($follower_id)) {
            throw new \InvalidArgumentException("Follower ID não pode estar vazio");
        }
        $this->follower_id = $follower_id;
        return $this;
    }

    public function getFollowingId(){
        return $this->user_id;
    }

    public function setFollowingId($user_id) {
        if (empty($user_id)) {
            throw new \InvalidArgumentException("Following ID não pode estar vazio");
        }
        $this->user_id = $user_id;
        return $this;
    }

    public function toArray(){
        $data = [
            'follower_id' => $this->follower_id,
            'user_id' => $this->user_id,
        ];
        if ($this->created_at) {
            $data['created_at'] = $this->created_at;
        }
        if ($this->id !== null) {
            $data['id'] = $this->id;
        }
        return $data;
    }
}