<?php

namespace App\Services\SpecialFields\Users;

use App\Services\SpecialFields\UserInterface;

class Grzesiek implements UserInterface
{
    private int $userId = 1;
    private string $date;

    public function check(int $userId): bool
    {
        if ($userId === $this->userId) {
            return true;
        }

        return false;
    }

    public function specialFields(): array
    {
        return [
            'A2' => 'aktualny kurs',
            'E2' => 'aktualny kurs',
            'I2' => 'aktualny kurs',
            'Q2' => $this->date,
        ];
    }

    public function setDate(string $date): void
    {
        $this->date = $date;
    }
}
