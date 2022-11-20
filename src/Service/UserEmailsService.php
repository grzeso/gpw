<?php

namespace App\Service;

use App\Entity\User;
use Symfony\Component\Mime\Address;

class UserEmailsService
{
    /**
     * @return array<int, Address>
     */
    public function convert(User $user): array
    {
        $mailCollection = $user->getUsersEmails();
        $mails = [];

        foreach ($mailCollection as $mailEntity) {
            $mails[] = new Address($mailEntity->getEmail());
        }

        return $mails;
    }
}
