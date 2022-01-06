<?php

namespace App\Services\SpecialFields;

use App\Services\SpecialFields\Users\Grzesiek;

class SpecialFieldsFactory
{
    private array $classes = [
        Grzesiek::class,
    ];

    public function factory(int $userId): ?UserInterface
    {
        /* @var UserInterface $class */
        foreach ($this->classes as $class) {
            $instance = new $class();
            if ($instance->check($userId)) {
                return new $instance();
            }
        }

        return null;
    }
}
