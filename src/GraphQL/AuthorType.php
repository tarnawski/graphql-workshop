<?php declare(strict_types=1);

namespace App\GraphQL;

use GraphQL\Type\Definition\ObjectType;
use GraphQL\Type\Definition\Type;

class AuthorType extends ObjectType
{
    public function __construct()
    {
        $config = [
            'fields' => [
                'identity' => [
                    'type' => Type::string(),
                    'description' => 'The identity of the author'
                ],
                'name' => [
                    'type' => Type::string(),
                    'description' => 'The name of the author'
                ],
                'email' => [
                    'type' => Type::string(),
                    'description' => 'The email of the author'
                ]
            ]
        ];

        parent::__construct($config);
    }
}
