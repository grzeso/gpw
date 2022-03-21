<?php

namespace App\Service\Settings;

use App\Entity\Settings;
use App\Repository\SettingsRepository;
use Doctrine\ORM\EntityManagerInterface;

class SettingsService
{
    private SettingsRepository $settingsRepository;
    private ?Settings $settings;
    private EntityManagerInterface $entityManager;

    public function __construct(SettingsRepository $settingsRepository, EntityManagerInterface $entityManager)
    {
        $this->settingsRepository = $settingsRepository;
        $this->settings = $this->settingsRepository->findOneBy(['name' => 'log_number']);
        $this->entityManager = $entityManager;
    }

    public function getLogNumber(): ?Settings
    {
        return $this->settings;
    }

    public function updateLogNumber(): void
    {
        $oldNumber = (int) $this->settings->getValue();
        ++$oldNumber;
        $this->settings->setValue((string) $oldNumber);

        $this->entityManager->persist($this->settings);
        $this->entityManager->flush();
    }
}
