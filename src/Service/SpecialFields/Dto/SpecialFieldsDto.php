<?php

namespace App\Service\SpecialFields\Dto;

use App\Helper\Users\AbstractUser;
use App\Helper\Users\Grzesiek;

class SpecialFieldsDto
{
    private array $collection = [];
    private AbstractUser $user;
    private array $data;

    public function __construct(Grzesiek $user)
    {
        $this->user = $user;
    }

    public function setUser(AbstractUser $user)
    {
        $this->user = $user;
    }

    public function setData(array $data)
    {
        $this->data = $data;
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
        $this->getDefinedFields($this->user->getDefinedFields());
        $this->getDynamicFields($this->user->getDynamicFields(), $this->data);

        return $this->collection;
    }
}
