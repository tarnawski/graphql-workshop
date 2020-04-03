<?php declare(strict_types=1);

namespace App\GraphQL\DataTransformer;

use App\Entity\Tag;

class TagToArrayTransformer
{
    public function transform(Tag $tag): array
    {
        return [
            'identity' => $tag->getId(),
            'name' => $tag->getName(),
        ];
    }
}
