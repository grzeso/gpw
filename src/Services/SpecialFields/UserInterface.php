<?php

namespace App\Services\SpecialFields;

interface UserInterface
{
    public function getSpecialFields(): array;

    public function setDate(string $date): void;

    public function check(int $userId): bool;
}
