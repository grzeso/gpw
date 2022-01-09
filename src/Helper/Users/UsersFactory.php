<?php

namespace App\Helper\Users;

class UsersFactory
{
    private array $classes = [
        Grzesiek::class,
    ];

    public function factory(int $userId): ?AbstractUser
    {
        /* @var AbstractUser $class */
        foreach ($this->classes as $class) {
            $instance = new $class();
            if ($instance->check($userId)) {
                return new $instance();
            }
        }

        return null;
    }
}
