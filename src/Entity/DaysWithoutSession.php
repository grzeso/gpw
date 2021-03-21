<?php

namespace App\Entity;

use App\Repository\DaysWithoutSessionRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=DaysWithoutSessionRepository::class)
 */
class DaysWithoutSession
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=10)
     */
    private $day;

    /**
     * @ORM\Column(type="datetime")
     */
    private $TS;

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

    public function getTS(): ?\DateTimeInterface
    {
        return $this->TS;
    }

    public function setTS(\DateTimeInterface $TS): self
    {
        $this->TS = $TS;

        return $this;
    }
}
