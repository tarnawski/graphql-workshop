<?php declare(strict_types=1);

namespace App\GraphQL;

use App\Entity\Post;
use App\Entity\Tag;
use App\Repository\PostRepository;
use App\Repository\TagRepository;
use GraphQL\Type\Definition\ObjectType;
use GraphQL\Type\Definition\ResolveInfo;
use GraphQL\Type\Definition\Type;

class QueryType extends ObjectType
{
    /** @var PostRepository */
    private $postRepository;

    /** @var TagRepository */
    private $tagRepository;

    /**
     * @param PostType $postType
     * @param TagType $tagType
     * @param PostRepository $postRepository
     * @param TagRepository $tagRepository
     */
    public function __construct(PostType $postType, TagType $tagType, PostRepository $postRepository, TagRepository $tagRepository)
    {
        $this->postRepository = $postRepository;
        $this->tagRepository = $tagRepository;

        $config = [
            'name' => 'Query',
            'fields' => [
                'posts' => [
                    'type' => Type::listOf($postType),
                    'args' => [
                        'limit' => [
                            'type' => Type::int(),
                            'description' => 'Number of stories to be returned',
                            'defaultValue' => 5
                        ]
                    ],
                ],
                'tags' => [
                    'type' => Type::listOf($tagType),
                ]
            ],
            'resolveField' => function($rootValue, $args, $context, ResolveInfo $info) {
                return $this->{$info->fieldName}($rootValue, $args, $context, $info);
            }
        ];

        parent::__construct($config);
    }

    private function posts($rootValue, $args, $context, ResolveInfo $info): array
    {
        return array_map(function (Post $post) {
            return [
                'identity' => $post->getId(),
                'title' => $post->getTitle(),
                'summary' => $post->getSummary(),
                'content' => $post->getContent(),
                'author' => [
                    'identity' => $post->getAuthor()->getId(),
                    'name' => $post->getAuthor()->getFullName(),
                    'email' => $post->getAuthor()->getEmail(),
                ],
                'tags' => array_map(function (Tag $tag) {
                    return [
                        'identity' => $tag->getId(),
                        'name' => $tag->getName()
                    ];
                }, $post->getTags()->toArray())
            ];
        }, $this->postRepository->findBy([], null, $args['limit']));
    }

    private function tags($rootValue, $args, $context, ResolveInfo $info): array
    {
        return array_map(function (Tag $tag) {
            return [
                'identity' => $tag->getId(),
                'name' => $tag->getName(),
            ];
        }, $this->tagRepository->findAll());
    }
}