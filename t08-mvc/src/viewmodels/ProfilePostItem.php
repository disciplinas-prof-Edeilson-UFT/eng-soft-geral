<?php
declare(strict_types=1);

namespace src\viewmodels;

use src\database\domain\Post;

class ProfilePostItem
{
    public function __construct(private Post $post) {

    }

    public function getPost(): Post { 
        return $this->post;
    }


    public function getFormattedData(): array 
    {
        return [
            'id' => $this->post->getId(),
            'photoUrl' => htmlspecialchars($this->post->getPhotoUrl() ?? ''),
            'description' => htmlspecialchars($this->post->getDescription() ?? ''),
            'uploadDate' => $this->post->getUploadDate(),
            'formattedDate' => $this->formatPostDate($this->post->getUploadDate()),
            'hasImage' => !empty($this->post->getPhotoUrl()),
            'hasDescription' => !empty($this->post->getDescription())
        ];
    }

    private function formatPostDate(?string $date): string{
        return $date ? date('d/m/Y H:i', strtotime($date)) : '';
    }
}
