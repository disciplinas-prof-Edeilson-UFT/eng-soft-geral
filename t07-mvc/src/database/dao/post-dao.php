<?php
namespace src\database\dao;
use core\mvc\IModelRepository;
use core\mvc\traits\ModelRepositoryTrait;
use Database;
use PDO;

require_once __DIR__ . '/../../../core/mvc/model-repository.php';
require_once __DIR__ . '/../../../core/mvc/traits/model-repository-trait.php';
require_once __DIR__ . '/../../../database.php';

interface IPostDAO extends IModelRepository{
    public function getPostsById($userId);
    public function getAllPosts();
}

class postDAO implements IPostDAO{
    use ModelRepositoryTrait;

    protected $db;

    public function __construct(IModelRepository $modelRepository)
    {
        $this->modelRepository = $modelRepository;
        $this->db = Database::getInstance()->getConnection();
    }

    public function getPostsById($userId) {

        $sql = "SELECT p.id, p.photo_url, p.upload_date, p.description, u.username, u.profile_pic_url 
                FROM posts p 
                JOIN users u ON p.user_id = u.id 
                WHERE p.user_id = :userId 
                ORDER BY p.upload_date DESC";

        $stmt = $this->db->prepare($sql);
        $stmt->execute([':userId' => $userId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getAllPosts() {
        $sql = "SELECT p.id, p.photo_url, p.user_id, p.upload_date, p.description, u.username, u.profile_pic_url 
                FROM posts p 
                JOIN users u ON p.user_id = u.id 
                ORDER BY p.upload_date DESC";

        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }



}