<?php

namespace App\Services\SpecialFields;

interface UserInterface
{
    public function specialFields();

    public function check(int $userId);
}
