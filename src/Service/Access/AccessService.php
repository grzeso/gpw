<?php

namespace App\Service\Access;

use App\Repository\DaysWithoutSessionRepository;
use App\Repository\LogRepository;
use App\Service\Access\Exception\NoAccessException;
use App\Service\Logger\Logger;
use DateTime;

class AccessService
{
    private DaysWithoutSessionRepository $daysWithoutSessionRepository;
    private LogRepository $logRepository;
    private Logger $logger;

    public function __construct(DaysWithoutSessionRepository $daysWithoutSessionRepository, LogRepository $logRepository)
    {
        $this->daysWithoutSessionRepository = $daysWithoutSessionRepository;
        $this->logRepository = $logRepository;
    }

    private static array $daysOfWeekWithoutSession = [6, 0];

    /**
     * @throws NoAccessException
     */
    public function isAccess(DateTime $date, ?int $allowed)
    {
        try {
            if ($allowed) {
                throw new NoAccessException(Logger::EVENT_EXTRA_ACCESS_ALLOWED_MESSAGE, Logger::EVENT_EXTRA_ACCESS_ALLOWED);
            }
            $this->isDayWithoutSession($date->format('d-m-Y'));
            $this->wasUsedOnDay($date->format('d-m-Y'));

            throw new NoAccessException(Logger::EVENT_ACCESS_ALLOWED_MESSAGE, Logger::EVENT_ACCESS_ALLOWED);
        } catch (NoAccessException $e) {
            $this->logger->logNoAccess($e);

            if (Logger::EVENT_ACCESS_NOT_ALLOWED === $e->getCode()) {
                exit($e->getMessage());
            }
        }
    }

    /**
     * @throws NoAccessException
     */
    private function wasUsedOnDay(string $date): void
    {
        $logs = $this->logRepository->findBy(['date' => $date, 'eventId' => Logger::EVENT_ACCESS_ALLOWED]);

        if ($logs) {
            throw new NoAccessException('Cron byl uzyty', Logger::EVENT_ACCESS_NOT_ALLOWED);
        }
    }

    /**
     * @throws NoAccessException
     */
    private function isDayWithoutSession(string $date): void
    {
        $date = DateTime::createFromFormat('d-m-Y', $date);

        if (in_array($date->format('w'), self::$daysOfWeekWithoutSession)) {
            throw new NoAccessException('Dzien bez sesji', Logger::EVENT_ACCESS_NOT_ALLOWED);
        }

        if ($this->daysWithoutSessionRepository->findOneBy(['day' => $date->format('Y-m-d')])) {
            throw new NoAccessException('Swieto - dzien bez sesji', Logger::EVENT_ACCESS_NOT_ALLOWED);
        }
    }

    public function setLogger(Logger $logger)
    {
        $this->logger = $logger;
    }
}
