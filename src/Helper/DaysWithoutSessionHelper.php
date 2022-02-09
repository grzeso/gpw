<?php

namespace App\Helper;

use App\Entity\DaysWithoutSession;
use App\Services\Logger\Logger;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Exception;

class DaysWithoutSessionHelper
{
    private static array $daysOfWeekWithoutSession = [6, 0];

    private static EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        self::$entityManager = $entityManager;
    }

    /**
     * @throws Exception
     */
    public function isDayWithoutSession(string $date): bool
    {
        $date = DateTime::createFromFormat('d-m-Y', $date);

        if (in_array($date->format('w'), self::$daysOfWeekWithoutSession)) {
            throw new Exception('Dzien bez sesji', Logger::EVENT_ACCESS_NOT_ALLOWED);
        }

        if (self::$entityManager->getRepository(DaysWithoutSession::class)->findOneBy(['day' => $date->format('Y-m-d')])) {
            throw new Exception('Swieto - dzien bez sesji', Logger::EVENT_ACCESS_NOT_ALLOWED);
        }

        return false;
    }
}
