<?php
declare(strict_types=1);

namespace src\viewmodels;

use src\database\domain\Post;

/**
 * View Model para exibir item de feed com dados do Post e do autor
 * mantem o domain (Post) limpo de atributos de usuário
 */
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
}
