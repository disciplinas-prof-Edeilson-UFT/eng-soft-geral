<?php

namespace src\database\mappers;

use src\database\domain\User;

class UserMapper {
    
    public function mapToUser(array $data): User {
        $user = new User(
            $data['username'], 
            $data['email'], 
            null, 
            $data['phone'] ?? null,
            $data['bio'] ?? null,
            $data['profile_pic_url'] ?? null
        );
        
        if (isset($data['id'])) {
            $user->setId($data['id']);
        }
        if (isset($data['password_hash'])) {
            $user->setPasswordHash($data['password_hash']);
        }
        
        if (isset($data['created_at'])) {
            $user->setCreatedAt($data['created_at']);
        }

        if (isset($data['count_followers'])) {
            $user->setCountFollowers($data['count_followers']);
        }

        if (isset($data['count_following'])) {
            $user->setCountFollowing($data['count_following']);
        }
        
        return $user;
    }

    public function mapToArray(User $user): array {
        return $user->toArray();
    }

    public function mapToUserProfile(array $data): User {
        $user = new User(
            $data['username'], 
            $data['email'], 
            null,                               
            $data['phone'] ?? null,
            $data['bio'] ?? null,
            $data['profile_pic_url'] ?? null
        );
        
        if (isset($data['id'])) {
            $user->setId($data['id']);
        } 
        if (isset($data['created_at'])) {
            $user->setCreatedAt($data['created_at']);
        }

        if (isset($data['count_followers'])) {
            $user->setCountFollowers($data['count_followers']);
        }
        
        if (isset($data['count_following'])) {
            $user->setCountFollowing($data['count_following']);
        }
                
        return $user;
    }

    public function mapToUserAuth(array $data): User {
        $user = new User(
            $data['username'], 
            $data['email'],
            null,
            null,
            null,
            null
        );
    
        if (isset($data['id'])) {
            $user->setId($data['id']);
        }
        
        if (isset($data['password_hash'])) {
            $user->setPasswordHash($data['password_hash']);
        }
        return $user;
    }
    
}