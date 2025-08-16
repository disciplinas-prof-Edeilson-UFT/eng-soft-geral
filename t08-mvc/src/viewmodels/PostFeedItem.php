<?php
declare(strict_types=1);

namespace src\viewmodels;

use src\database\domain\Post;

class PostFeedItem
{
    public function __construct(
        private Post $post,
        private string $username,
        private ?string $profilePicUrl
    ) {}

    public function getPost(): Post { return $this->post;}
    public function getUsername(): string { return $this->username;}
    public function getProfilePicUrl(): ?string { return $this->profilePicUrl;}

    public function getFormattedData(): array 
    {
        return [
            'username' => htmlspecialchars($this->getUsername()),
            'profilePhoto' => $this->getFormattedProfilePhoto(),
            'profileUrl' => '/profile/' . $this->post->getUserId(),
            'postImageUrl' => $this->getFormattedPostImage(),
            'description' => htmlspecialchars($this->post->getDescription() ?? ''),
            'formattedDate' => $this->getFormattedDate(),
            'uploadDate' => $this->post->getUploadDate(),
            'hasImage' => !empty($this->post->getPhotoUrl()),
            'hasDescription' => !empty($this->post->getDescription()),
            'userId' => $this->post->getUserId()
        ];
    }

    private function getFormattedProfilePhoto(): string
    {
        return $this->profilePicUrl 
            ? '/public/uploads/avatars/' . htmlspecialchars($this->profilePicUrl)
            : '/public/img/profile.svg';
    }

    private function getFormattedPostImage(): ?string
    {
        return $this->post->getPhotoUrl() 
            ? '/public/uploads/feed/' . htmlspecialchars($this->post->getPhotoUrl())
            : null;
    }

    private function getFormattedDate(): string
    {
        $date = $this->post->getUploadDate();
        return $date ? 'Publicado em: ' . date('d/m/Y H:i', strtotime($date)) : '';
    }
}
