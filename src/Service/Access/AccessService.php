<?php

namespace App\Service\Access;

use App\Repository\DaysWithoutSessionRepository;
use App\Repository\LogRepository;
use App\Service\Logger\Logger;
use DateTime;
use Exception;

class AccessService
{
    private DaysWithoutSessionRepository $daysWithoutSessionRepository;
    private LogRepository $logRepository;

    public function __construct(DaysWithoutSessionRepository $daysWithoutSessionRepository, LogRepository $logRepository)
    {
        $this->daysWithoutSessionRepository = $daysWithoutSessionRepository;
        $this->logRepository = $logRepository;
    }

    private static array $daysOfWeekWithoutSession = [6, 0];

    /**
     * @throws Exception
     */
    public function isAccess(string $date, ?int $allowed): bool
    {
        if ($allowed) {
            throw new Exception(Logger::EVENT_EXTRA_ACCESS_ALLOWED_MESSAGE, Logger::EVENT_EXTRA_ACCESS_ALLOWED);
        }
        $this->isDayWithoutSession($date);
        $this->wasUsedOnDay($date);

        throw new Exception(Logger::EVENT_ACCESS_ALLOWED_MESSAGE, Logger::EVENT_ACCESS_ALLOWED);
    }

    /**
     * @throws Exception
     */
    private function wasUsedOnDay(string $date): void
    {
        $logs = $this->logRepository->findBy(['date' => $date, 'eventId' => Logger::EVENT_ACCESS_ALLOWED]);

        if ($logs) {
            throw new Exception('Cron byl uzyty', Logger::EVENT_ACCESS_NOT_ALLOWED);
        }
    }

    /**
     * @throws Exception
     */
    private function isDayWithoutSession(string $date): void
    {
        $date = DateTime::createFromFormat('d-m-Y', $date);

        if (in_array($date->format('w'), self::$daysOfWeekWithoutSession)) {
            throw new Exception('Dzien bez sesji', Logger::EVENT_ACCESS_NOT_ALLOWED);
        }

        if ($this->daysWithoutSessionRepository->findOneBy(['day' => $date->format('Y-m-d')])) {
            throw new Exception('Swieto - dzien bez sesji', Logger::EVENT_ACCESS_NOT_ALLOWED);
        }
    }
}
