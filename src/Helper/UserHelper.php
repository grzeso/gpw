<?php

namespace App\Helper;

use App\Entity\User;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;

class UserHelper
{
    /**
     * @var EntityManager
     */
    private static $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        self::$entityManager = $entityManager;
    }

    public static function getUserMails(int $userId): array
    {
        $user = self::$entityManager->getRepository(User::class)->findOneBy(['id' => $userId]);
        $mailCollection = $user->getUsersEmails();

        $mails = [];

        foreach ($mailCollection as $mailEntity) {
            $mails[] = $mailEntity->getEmail();
        }
        return $mails;
    }
}
