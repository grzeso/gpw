<?php

namespace App\Helper;

use App\Entity\Log;
use App\Services\Logger\Logger;
use Doctrine\ORM\EntityManagerInterface;
use Exception;

class AccessHelper
{
    private EntityManagerInterface $entityManager;
    private DaysWithoutSessionHelper $dwss;

    public function __construct(EntityManagerInterface $entityManager, DaysWithoutSessionHelper $dwss)
    {
        $this->entityManager = $entityManager;
        $this->dwss = $dwss;
    }

    /**
     * @throws Exception
     */
    public function isAccess(string $date, ?int $allowed): bool
    {
        if ($allowed) {
            throw new Exception(Logger::EVENT_EXTRA_ACCESS_ALLOWED_MESSAGE, Logger::EVENT_EXTRA_ACCESS_ALLOWED);
        }
        $this->dwss->isDayWithoutSession($date);
        $this->wasUsedOnDay($date);

        throw new Exception(Logger::EVENT_ACCESS_ALLOWED_MESSAGE, Logger::EVENT_ACCESS_ALLOWED);
    }

    /**
     * @throws Exception
     */
    public function wasUsedOnDay(string $date): bool
    {
        $logs = $this
            ->entityManager
            ->getRepository(Log::class)
                ->findBy(['date' => $date, 'eventId' => Logger::EVENT_ACCESS_ALLOWED]);

        if ($logs) {
            throw new Exception('Cron byl uzyty', Logger::EVENT_ACCESS_NOT_ALLOWED);
        }

        return false;
    }
}
