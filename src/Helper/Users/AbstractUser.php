<?php

namespace App\Helper\Users;

abstract class AbstractUser
{
    protected int $userId;

    abstract public function getDefinedFields();

    abstract public function getDynamicFields();

    abstract public function getUserId();

    public function check(int $userId): bool
    {
        if ($userId === $this->userId) {
            return true;
        }

        return false;
    }
}
