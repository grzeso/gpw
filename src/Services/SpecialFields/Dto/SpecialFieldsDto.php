<?php

namespace App\Services\SpecialFields\Dto;

use App\Helper\Users\AbstractUser;

class SpecialFieldsDto
{
    private array $collection = [];

    public function __construct(AbstractUser $user, array $data)
    {
        $this->getDefinedFields($user->getDefinedFields());
        $this->getDynamicFields($user->getDynamicFields(), $data);
    }

    private function getDefinedFields(array $specialFields): void
    {
        if (!$specialFields) {
            return;
        }

        foreach ($specialFields as $key => $specialField) {
            $this->set($key, $specialField);
        }
    }

    private function getDynamicFields(array $fields, array $data): void
    {
        if (!$fields || !$data) {
            return;
        }

        foreach ($fields as $key => $specialField) {
            $this->set($key, $data[$specialField]);
        }
    }

    private function set($key, $data): void
    {
        $this->collection[$key] = $data;
    }

    public function get(): array
    {
        return $this->collection;
    }
}
