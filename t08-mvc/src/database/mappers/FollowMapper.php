<?php

namespace src\database\mappers;

use src\database\domain\Follow;

class FollowMapper {

    public function mapToFollow(array $data): Follow {
        $follow = new Follow($data['follower_id'], $data['following_id']);

        if (isset($data['id'])) {
            $follow->setId($data['id']);
        }
        if (isset($data['created_at'])) {
            $follow->setCreatedAt($data['created_at']);
        }
        return $follow;
    }

    public function mapToArray(Follow $follow): array {
        return $follow->toArray();
    }
}
