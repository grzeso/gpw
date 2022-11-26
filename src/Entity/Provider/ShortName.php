<?php

namespace App\Entity\Provider;

use App\Entity\Stock\Stock;
use App\Repository\NameDictionaryRepository;
use DateTimeInterface;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: NameDictionaryRepository::class)]
class ShortName
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: Types::INTEGER)]
    private ?int $id = null;

    #[ORM\Column(type: Types::INTEGER)]
    private ?int $provider = null;

    #[ORM\Column(type: Types::STRING, length: 100)]
    private ?string $nameInProvider = null;

    #[ORM\ManyToOne(inversedBy: 'shortNames')]
    private ?Stock $stock = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?DateTimeInterface $createdAt = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getProvider(): ?int
    {
        return $this->provider;
    }

    public function setProvider(int $provider): self
    {
        $this->provider = $provider;

        return $this;
    }

    public function getNameInProvider(): ?string
    {
        return $this->nameInProvider;
    }

    public function setNameInProvider(string $nameInProvider): self
    {
        $this->nameInProvider = $nameInProvider;

        return $this;
    }

    public function getStock(): ?Stock
    {
        return $this->stock;
    }

    public function setStock(?Stock $stock): self
    {
        $this->stock = $stock;

        return $this;
    }

    public function getCreatedAt(): ?DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(DateTimeInterface $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }
}
