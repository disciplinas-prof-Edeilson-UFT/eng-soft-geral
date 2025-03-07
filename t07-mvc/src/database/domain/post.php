<?php 
namespace src\database\domain;

class Post{
    public $id;
    public $user_id;
    public $photo_url;
    public $upload_date;
    public $description;

    public function __construct($user_id, $photo_url, $description = null, $upload_date = null) {
        $this->user_id = $user_id;
        $this->photo_url = $photo_url;
        $this->description = $description;
        $this->upload_date = date('Y-m-d H:i:s', strtotime($upload_date));
    }

    public function getId(){
        return $this->id;
    }

    public function getUserId(){
        return $this->user_id;
    }

    public function setUserId($user_id) {
        if (empty($user_id)) {
            throw new \InvalidArgumentException("User_id não pode estar vazio");
        }
        $this->user_id = $user_id;
        return $this;
    }

    public function getPhotoUrl(){
        return $this->photo_url;
    }

    public function setPhotoUrl($photo_url) {
        if (empty($photo_url)) {
            throw new \InvalidArgumentException("Photo_url não pode estar vazio");
        }
        $this->photo_url = $photo_url;
        return $this;
    }

    public function getUploadDate(){
        return $this->upload_date;
    }

    public function getDescription(){
        return $this->description;
    }

    public function setDescription($description) {
        $this->description = $description;
        return $this;
    }

    public function toArray() {
        $data = [
            'user_id' => $this->user_id,
            'photo_url' => $this->photo_url,
            'description' => $this->description,
        ];
        if (!empty($this->id)) {
            
            $data['id'] = $this->id;
        }
        if (!empty($this->upload_date)) {
            $data['upload_date'] = $this->upload_date;
        }

        return $data;
    }
}