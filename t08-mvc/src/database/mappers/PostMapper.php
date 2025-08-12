<?php
declare(strict_types=1);
namespace src\database\mappers;

use src\database\domain\Post;

class PostMapper {
    public function mapToPost(array $data): Post {
        $post= new Post((int)$data['user_id'], $data['photo_url'], $data['description'] ?? null);
        if (isset($data['id'])) { $post->setId((int)$data['id']); }
        if (isset($data['upload_date'])) { $post->setUploadDate($data['upload_date']); }
        return $post;
    }

    public function mapToPersistenceArray(Post $post): array {
        $data = [
            'user_id' => $post->getUserId(),
            'photo_url' => $post->getPhotoUrl(),
            'description' => $post->getDescription(),
        ];
        if ($post->getId() !== null) { $data['id'] = $post->getId(); }
        if ($post->getUploadDate() !== null) { $data['upload_date'] = $post->getUploadDate(); }
        return $data;
    }
}
