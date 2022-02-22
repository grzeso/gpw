<?php

namespace App\Helper;

use App\Repository\UserRepository;

class UserHelper
{
    private UserRepository $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function getUserMails(int $userId): array
    {
        $user = $this->userRepository->findOneBy(['id' => $userId]);
        $mailCollection = $user->getUsersEmails();

        $mails = [];

        foreach ($mailCollection as $mailEntity) {
            $mails[] = $mailEntity->getEmail();
        }

        return $mails;
    }
}
