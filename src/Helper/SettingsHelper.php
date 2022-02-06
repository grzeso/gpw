<?php

namespace App\Helper;

use App\Entity\Settings;
use Doctrine\ORM\EntityManagerInterface;

class SettingsHelper
{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function updateLogNumber(Settings $number)
    {
        $oldNumber = (int) $number->getValue();
        ++$oldNumber;
        $number->setValue((string) $oldNumber);

        $this->entityManager->persist($number);
        $this->entityManager->flush();
    }
}
