<?php
declare(strict_types=1);

namespace src\viewmodels;

use src\database\domain\User;

class ProfileData
{
    public function __construct(
        private User $user,
        private int $userId,
        private int $loggedUserId,
        private bool $isFollowing,
        private string $username
    ) {}

 
    public function getFormattedData(): array{
        return [
            'user' => $this->user,
            'user_id' => $this->userId,
            'loggedUserId' => $this->loggedUserId,
            'isOwnProfile' => $this->userId === $this->loggedUserId,
            'profilePhoto' => $this->getFormattedProfilePhoto(),
            'username' => htmlspecialchars($this->user->getUsername()),
            'bio' => htmlspecialchars($this->user->getBio() ?? 'Sem biografia'),
            'followingCount' => htmlspecialchars((string)($this->user->getCountFollowing() ?? 0)),
            'followersCount' => htmlspecialchars((string)($this->user->getCountFollowers() ?? 0)),
            'isFollowing' => $this->isFollowing,
            'followButtonText' => $this->isFollowing ? 'Deixar de seguir' : 'Seguir',
            'followAction' => $this->isFollowing ? 'unfollow' : 'follow'
        ];
    }


    private function getFormattedProfilePhoto(): string
    {
        return $this->user->getProfilePicUrl() ? 
            '/public/uploads/avatars/' . htmlspecialchars($this->user->getProfilePicUrl()) : 
            '/public/img/profile.svg';
    }
}
