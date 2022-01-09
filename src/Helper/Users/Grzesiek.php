<?php

namespace App\Services\SpecialFields\Users;

namespace App\Helper\Users;

class Grzesiek extends AbstractUser
{
    protected int $userId = 1;

    public function getUserId(): int
    {
        return $this->userId;
    }

    public function getDefinedFields(): array
    {
        return [
            'A2' => 'aktualny kurs',
            'E2' => 'aktualny kurs',
            'I2' => 'aktualny kurs',
        ];
    }

    public function getDynamicFields(): array
    {
        return [
            'Q2' => 'date',
        ];
    }
}
