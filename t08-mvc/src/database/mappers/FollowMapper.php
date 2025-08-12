<?php
declare(strict_types=1);
namespace src\database\mappers;

use src\database\domain\Follow;

class FollowMapper {

    public function mapToFollow(array $data): Follow {
        $follow = new Follow((int)$data['follower_id'], (int)$data['following_id']);
        if (isset($data['id'])) { $follow->setId((int)$data['id']); }
        if (isset($data['created_at'])) { $follow->setCreatedAt($data['created_at']); }
        return $follow;
    }

    public function mapToPersistenceArray(Follow $follow): array {
        $data = [
            'follower_id' => $follow->getFollowerId(),
            'following_id' => $follow->getFollowingId(),
        ];
        if ($follow->getId() !== null) { $data['id'] = $follow->getId(); }
        if ($follow->getCreatedAt() !== null) { $data['created_at'] = $follow->getCreatedAt(); }
        return $data;
    }
}
