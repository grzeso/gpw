<?php

namespace App\Service\Logger;

use App\Entity\Log;
use App\Entity\User;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;

class Logger
{
    public const EVENT_START = 1;
    public const EVENT_ACCESS_ALLOWED = 2;
    public const EVENT_ACCESS_NOT_ALLOWED = 3;
    public const EVENT_EXTRA_ACCESS_ALLOWED = 4;
    public const EVENT_ERROR = 5;
    public const EVENT_SENT = 6;

    public const EVENT_START_MESSAGE = 'Start crona';
    public const EVENT_ACCESS_ALLOWED_MESSAGE = 'Dostep dozwolony';
    public const EVENT_ACCESS_NOT_ALLOWED_MESSAGE = 'Dostep nie dozwolony';
    public const EVENT_EXTRA_ACCESS_ALLOWED_MESSAGE = 'Dostep wymuszony parametrem';
    public const EVENT_ERROR_MESSAGE = 'Blad';
    public const EVENT_SENT_MESSAGE = 'Czy wyslany';

    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function log(string $description, User $user, int $eventId, int $useId, string $dateLookingFor, $params = null)
    {
        $log = new Log();
        $log->setDescription($description);
        $log->setUser($user);
        $log->setEventId($eventId);
        $log->setUseId($useId);
        $log->setDate($dateLookingFor);
        $log->setParams(json_encode($params, JSON_FORCE_OBJECT));
        $log->setTs(new DateTime());

        $this->entityManager->persist($log);
        $this->entityManager->flush();
    }
}
