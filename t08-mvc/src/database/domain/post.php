<?php 
namespace src\database\domain;

class Post{
    private $id;
    private $user_id;
    private $photo_url;
    private $upload_date;
    private $description;

    public function __construct($user_id, $photo_url, $description = null) {
        $this->user_id = $user_id;
        $this->photo_url = $photo_url;
        $this->description = $description;
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

    public function getUserId(){
        return $this->user_id;
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

    public function setUploadDate($upload_date) {
        if (is_numeric($upload_date)) {
            $this->upload_date = date('Y-m-d H:i:s', $upload_date);
        }
        else if ($upload_date === null) {
            $this->upload_date = date('Y-m-d H:i:s');
        }
        return $this;
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
            'description' => $this->description,
        ];
        if (!empty($this->id)) {
            
            $data['id'] = $this->id;
        }
        if (!empty($this->upload_date)) {
            $data['upload_date'] = $this->upload_date;
        }

        if (!empty($this->photo_url)) {
            $data['photo_url'] = $this->photo_url;
        }
        return $data;
    }
}