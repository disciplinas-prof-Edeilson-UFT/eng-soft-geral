<?php 
namespace src\database\domain;

class Post{
    private $id;
    private $userID;
    private $photoUrl;
    private $uploadDate;
    private $description;

    public function __construct($userID, $photoUrl, $description = null) {
        $this->userID = $userID;
        $this->photoUrl = $photoUrl;
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
        return $this->userID;
    }

    public function getPhotoUrl(){
        return $this->photoUrl;
    }

    public function setPhotoUrl($photoUrl) {
        if (empty($photoUrl)) {
            throw new \InvalidArgumentException("PhotoUrl não pode estar vazio");
        }
        $this->photoUrl= $photoUrl;
        return $this;
    }

    public function getUploadDate(){
        return $this->uploadDate;
    }

    public function setUploadDate($uploadDate) {
        if (is_numeric($uploadDate)) {
            $this->uploadDate= date('Y-m-d H:i:s', $uploadDate);
        }
        else if ($uploadDate === null) {
            $this->uploadDate = date('Y-m-d H:i:s');
        }
        return $this;
    }

    public function getDescription(){
        return $this->description;
    }

    public function setDescription($description) {
        $this->description= $description;
        return $this;
    }

    public function toArray(): array {
        $data = [
            'user_id' => $this->userID,
            'photo_url' => $this->photoUrl,
            'description' => $this->description,
        ];
        
        if ($this->id !== null) {
            $data['id']= $this->id;
        }
        if ($this->uploadDate !== null) {
            $data['upload_date']= $this->uploadDate;
        }
        
        return $data;
    }
}