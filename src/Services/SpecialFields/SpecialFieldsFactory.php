<?php

namespace App\Services\SpecialFields;

class SpecialFieldsFactory
{
    public function factory(int $userId): array
    {
        $class = 'App\Services\SpecialFields\User'.$userId;
        if (class_exists($class)) {
            return (new $class())->specialFields();
        }

        return [];
    }
}
