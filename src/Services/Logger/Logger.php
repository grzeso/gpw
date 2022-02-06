<?php

namespace App\Services\Logger;

use App\Entity\Log;
use App\Entity\User;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;

class Logger
{
    public const EVENT_START = 1;
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function log(string $description, User $user, int $eventId, int $useId, $params = null)
    {
        $log = new Log();
        $log->setDescription($description);
        $log->setUser($user);
        $log->setEventId($eventId);
        $log->setUseId($useId);
        $log->setParams(json_encode($params, JSON_FORCE_OBJECT));
        $log->setTs(new DateTime());

        $this->entityManager->persist($log);
        $this->entityManager->flush();
    }
}
