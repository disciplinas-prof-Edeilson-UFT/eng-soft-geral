<?php

namespace src\database\mappers;

use src\database\domain\Post;

class PostMapper {

    public function mapToPost(array $data): Post {
        $post= new Post($data['user_id'], $data['photo_url'], $data['description']);
        
        if (isset($data['id'])) {
            $post->setId($data['id']);
        }
        if (isset($data['upload_date'])) {
            $post->setUploadDate($data['upload_date']);
        }
        return $post;
    }

    public function mapToArray(Post $post): array {
        return $post->toArray();
    }
}
