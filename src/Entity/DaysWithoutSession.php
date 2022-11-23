<?php

namespace App\Entity;

use App\Repository\DaysWithoutSessionRepository;
use DateTimeInterface;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: DaysWithoutSessionRepository::class)]
class DaysWithoutSession
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: Types::INTEGER)]
    private ?int $id = null;

    #[ORM\Column(type: Types::STRING, length: 10)]
    private ?string $day = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?DateTimeInterface $TS = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDay(): ?string
    {
        return $this->day;
    }

    public function setDay(string $day): self
    {
        $this->day = $day;

        return $this;
    }

    public function getTS(): ?DateTimeInterface
    {
        return $this->TS;
    }

    public function setTS(DateTimeInterface $TS): self
    {
        $this->TS = $TS;

        return $this;
    }
}
