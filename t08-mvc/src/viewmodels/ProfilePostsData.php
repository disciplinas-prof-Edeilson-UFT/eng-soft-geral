<?php
declare(strict_types=1);

namespace src\viewmodels;


class ProfilePostsData
{
    public function __construct(private array $profilePostItems,private string $username, private int $userId, private int $loggedUserId) {

    }

    public function getFormattedData(): array{
        $preparedPosts= $this->formatPosts();
        
        return [
            'userPosts' => $preparedPosts,
            'hasUserPosts' => !empty($preparedPosts),
            'postsCount' => count($preparedPosts),
            'postsCountText' => count($preparedPosts) . ' post' . (count($preparedPosts) !== 1 ? 's' : ''),
            'uploadText' => empty($preparedPosts) ? 'Adicionar primeira foto' : 'Adicionar nova foto',
            'noPostsTitle' => $this->getNoPostsTitle(),
            'noPostsMessage' => $this->getNoPostsMessage()
        ];
    }

    private function formatPosts(): array{
        $formattedPosts = [];
        
        foreach ($this->profilePostItems as $postItem) {
            $formattedPosts[] = $postItem->getFormattedData();
        }
        
        return $formattedPosts;
    }

    private function getNoPostsTitle(): string{
        return $this->userId === $this->loggedUserId 
            ? 'Você ainda não tem posts' 
            : htmlspecialchars($this->username) . ' ainda não tem posts';
    }

    private function getNoPostsMessage(): string{
        return $this->userId === $this->loggedUserId 
            ? 'Comece compartilhando sua primeira foto!' 
            : 'Quando ' . htmlspecialchars($this->username) . ' compartilhar algo, aparecerá aqui.';
    }
}
