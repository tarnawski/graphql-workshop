<?php declare(strict_types=1);

namespace App\GraphQL\Type;

use GraphQL\Type\Definition\ObjectType;
use GraphQL\Type\Definition\Type;

class PostType extends ObjectType
{
    /**
     * @param TagType $tagType
     * @param AuthorType $authorType
     */
    public function __construct(TagType $tagType, AuthorType $authorType)
    {
        $config = [
            'fields' => [
                'identity' => [
                    'type' => Type::string(),
                    'description' => 'The identity of the post'
                ],
                'title' => [
                    'type' => Type::string(),
                    'description' => 'The title of the post'
                ],
                'summary' => [
                    'type' => Type::string(),
                    'description' => 'The summary of the post'
                ],
                'content' => [
                    'type' => Type::string(),
                    'description' => 'The content of the post'
                ],
                'author' => [
                    'type' => $authorType,
                    'description' => 'The content of the post'
                ],
                'tags' => [
                    'type' => Type::listOf($tagType),
                    'description' => 'The tags of the post'
                ],
            ]
        ];

        parent::__construct($config);
    }
}
