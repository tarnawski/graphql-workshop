<?php declare(strict_types=1);

namespace App\GraphQL;

use App\GraphQL\Factory\PostFactory;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use GraphQL\Type\Definition\ObjectType;
use GraphQL\Type\Definition\Type;

class MutationType extends ObjectType
{
    private EntityManagerInterface $entityManager;
    private PostFactory $postFactory;

    public function __construct(EntityManagerInterface $entityManager, PostFactory $postFactory)
    {
        $this->entityManager = $entityManager;
        $this->postFactory = $postFactory;

        $config = [
            'name' => 'Mutation',
            'fields' => [
                'createPost' => [
                    'args' => [
                        'title' => Type::string(),
                        'summary' => Type::string(),
                        'content' => Type::string(),
                    ],
                    'type' => new ObjectType([
                        'name' => 'CreatePostOutput',
                        'fields' => [
                            'status' => ['type' => Type::string()],
                            'message' => ['type' => Type::string()],
                        ]
                    ]),
                    'resolve' => function ($calc, array $args) {
                        try {
                            $post = $this->postFactory->build($args['title'], $args['summary'], $args['content']);
                            $this->entityManager->persist($post);
                            $this->entityManager->flush();
                        } catch (Exception $exception) {
                            return ['status' => 'error', 'message' => $exception->getMessage()];
                        }

                        return ['status' => 'success'];
                    },
                ],
            ]
        ];

        parent::__construct($config);
    }
}
