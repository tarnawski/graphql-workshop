<?php declare(strict_types=1);

namespace App\GraphQL\Factory;

use App\Entity\Post;
use Symfony\Component\String\Slugger\SluggerInterface;

class PostFactory
{
    private SluggerInterface $slugger;

    public function __construct(SluggerInterface $slugger)
    {
        $this->slugger = $slugger;
    }

    public function build(string $title, string $summary, string $content): Post
    {
        $post = new Post();
        $post->setTitle($title);
        $post->setSummary($summary);
        $post->setContent($content);
        $post->setSlug($this->slugger->slug($title)->toString());

        return $post;
    }
}