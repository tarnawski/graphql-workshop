<?php declare(strict_types=1);

namespace App\GraphQL;

use App\Entity\Post;
use App\Entity\Tag;
use App\GraphQL\DataTransformer\PostToArrayTransformer;
use App\GraphQL\DataTransformer\TagToArrayTransformer;
use App\GraphQL\Type\PostType;
use App\GraphQL\Type\TagType;
use App\Repository\PostRepository;
use App\Repository\TagRepository;
use GraphQL\Type\Definition\ObjectType;
use GraphQL\Type\Definition\ResolveInfo;
use GraphQL\Type\Definition\Type;

class QueryType extends ObjectType
{
    private PostRepository $postRepository;
    private TagRepository $tagRepository;
    private PostToArrayTransformer $postToArrayTransformer;
    private TagToArrayTransformer $tagToArrayTransformer;

    public function __construct(
        PostType $postType,
        TagType $tagType,
        PostRepository $postRepository,
        TagRepository $tagRepository,
        PostToArrayTransformer $postToArrayTransformer,
        TagToArrayTransformer $tagToArrayTransformer
    ) {
        $this->postRepository = $postRepository;
        $this->tagRepository = $tagRepository;
        $this->postToArrayTransformer = $postToArrayTransformer;
        $this->tagToArrayTransformer = $tagToArrayTransformer;

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
            'resolveField' => function ($rootValue, $args, $context, ResolveInfo $info) {
                return $this->{$info->fieldName}($rootValue, $args, $context, $info);
            }
        ];

        parent::__construct($config);
    }

    private function posts($rootValue, $args, $context, ResolveInfo $info): array
    {
        return array_map(
            fn (Post $post) => $this->postToArrayTransformer->transform($post),
            $this->postRepository->findBy([], null, $args['limit'])
        );
    }

    private function tags($rootValue, $args, $context, ResolveInfo $info): array
    {
        return array_map(
            fn (Tag $tag) => $this->tagToArrayTransformer->transform($tag),
            $this->tagRepository->findAll()
        );
    }
}