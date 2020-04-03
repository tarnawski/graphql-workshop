<?php declare(strict_types=1);

namespace App\GraphQL\DataTransformer;

use App\Entity\Post;

class PostToArrayTransformer
{
    /** @var UserToArrayTransformer */
    private $userToArrayTransformer;

    public function __construct(UserToArrayTransformer $userToArrayTransformer)
    {
        $this->userToArrayTransformer = $userToArrayTransformer;
    }

    public function transform(Post $post): array
    {
        return [
            'identity' => $post->getId(),
            'title' => $post->getTitle(),
            'summary' => $post->getSummary(),
            'content' => $post->getContent(),
            'author' => $this->userToArrayTransformer->transform($post->getAuthor())
        ];
    }
}