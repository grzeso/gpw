<?php

namespace App\Service\Logger;

use App\Entity\Log;
use App\Entity\Settings;
use App\Entity\User\User;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Exception;

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
    private User $user;
    private Settings $settings;
    private DateTime $date;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function setUser(User $user): void
    {
        $this->user = $user;
    }

    public function setLogId(Settings $settings): void
    {
        $this->settings = $settings;
    }

    public function setDate(DateTime $date): void
    {
        $this->date = $date;
    }

    /**
     * @param array<string, string|int>|null $params
     */
    public function log(string $description, int $eventId, array $params = null): void
    {
        $log = new Log();
        $log->setDescription($description);
        $log->setUser($this->user);
        $log->setEventId($eventId);
        $log->setUseId((int) $this->settings->getValue());
        $log->setDate($this->date->format('d-m-Y'));
        $log->setParams(json_encode($params, JSON_FORCE_OBJECT));
        $log->setTs(new DateTime());

        $this->entityManager->persist($log);
        $this->entityManager->flush();
    }

    public function logError(Exception $e): void
    {
        $this->log(
            Logger::EVENT_ERROR_MESSAGE,
            Logger::EVENT_ERROR,
            [
                'error_message' => $e->getMessage(),
                'error_mfile' => $e->getFile(),
                'error_line' => $e->getLine(),
                'error_trace' => json_encode($e->getTrace()),
            ]
        );
    }

    public function logSent(int $result): void
    {
        $this->log(
            self::EVENT_SENT_MESSAGE,
            self::EVENT_SENT,
            [
                'sent_result' => $result,
            ]
        );
    }

    public function logStart(int $userId, string $originalDate): void
    {
        $this->log(
            self::EVENT_START_MESSAGE,
            self::EVENT_START,
            [
                'userId' => $userId,
                'date' => $originalDate,
            ]
        );
    }

    public function logNoAccess(Exception $e): void
    {
        $this->log(
            $e->getMessage(),
            $e->getCode(),
        );
    }
}
