<?php declare(strict_types=1);

namespace App\GraphQL;

use App\Entity\Post;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use GraphQL\Type\Definition\ObjectType;
use GraphQL\Type\Definition\Type;
use Symfony\Component\String\Slugger\SluggerInterface;

class MutationType extends ObjectType
{
    /** @var UserRepository */
    private $userRepository;

    /** @var EntityManagerInterface */
    private $entityManager;

    /** @var SluggerInterface */
    private $slugger;

    public function __construct(UserRepository $userRepository, EntityManagerInterface $entityManager, SluggerInterface $slugger)
    {
        $this->userRepository = $userRepository;
        $this->entityManager = $entityManager;
        $this->slugger = $slugger;

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
                    'resolve' => function ($calc, $args) {
                        try {
                            $post = $this->createPost($args['title'], $args['summary'], $args['content']);
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

    private function createPost(string $title, string $summary, string $content): Post
    {
        $post = new Post();
        $post->setTitle($title);
        $post->setSummary($summary);
        $post->setContent($content);
        $post->setAuthor($this->userRepository->find(1));
        $post->setSlug($this->slugger->slug($title)->toString());

        return $post;
    }
}
