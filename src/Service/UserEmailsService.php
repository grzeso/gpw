<?php

namespace App\Service;

use App\Entity\User;

class UserEmailsService
{
    /**
     * @return array<int, string|null>
     */
    public function convert(User $user): array
    {
        $mailCollection = $user->getUsersEmails();
        $mails = [];

        foreach ($mailCollection as $mailEntity) {
            $mails[] = $mailEntity->getEmail();
        }

        return $mails;
    }
}
