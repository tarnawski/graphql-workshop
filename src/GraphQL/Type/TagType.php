<?php declare(strict_types=1);

namespace App\GraphQL\Type;

use GraphQL\Type\Definition\ObjectType;
use GraphQL\Type\Definition\Type;

class TagType extends ObjectType
{
    public function __construct()
    {
        $config = [
            'fields' => [
                'identity' => [
                    'type' => Type::string(),
                    'description' => 'The identity of the tag'
                ],
                'name' => [
                    'type' => Type::string(),
                    'description' => 'The name of the tag'
                ]
            ]
        ];

        parent::__construct($config);
    }
}
