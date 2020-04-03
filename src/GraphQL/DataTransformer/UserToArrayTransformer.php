<?php declare(strict_types=1);

namespace App\GraphQL\DataTransformer;

use App\Entity\User;

class UserToArrayTransformer
{
    public function transform(User $user): array
    {
        return [
            'identity' => $user->getId(),
            'name' => $user->getFullName(),
            'email' => $user->getEmail(),
        ];
    }
}