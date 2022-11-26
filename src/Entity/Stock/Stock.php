<?php

namespace App\Entity\Stock;

use App\Entity\Provider\ShortName;
use App\Entity\User\UserStock;
use App\Repository\Stock\StockRepository;
use DateTimeInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: StockRepository::class)]
class Stock
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 50)]
    private ?string $name = null;

    #[ORM\OneToMany(mappedBy: 'stock', targetEntity: ShortName::class)]
    private Collection $shortNames;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?DateTimeInterface $createAt = null;

    #[ORM\OneToMany(mappedBy: 'stock', targetEntity: UserStock::class)]
    private Collection $stocks;

    #[ORM\OneToMany(mappedBy: 'stock', targetEntity: Rate::class)]
    private Collection $rates;

    public function __construct()
    {
        $this->shortNames = new ArrayCollection();
        $this->stocks = new ArrayCollection();
        $this->rates = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return Collection<int, ShortName>
     */
    public function getShortNames(): Collection
    {
        return $this->shortNames;
    }

    public function addShortName(ShortName $shortName): self
    {
        if (!$this->shortNames->contains($shortName)) {
            $this->shortNames->add($shortName);
            $shortName->setStock($this);
        }

        return $this;
    }

    public function removeShortName(ShortName $shortName): self
    {
        if ($this->shortNames->removeElement($shortName)) {
            // set the owning side to null (unless already changed)
            if ($shortName->getStock() === $this) {
                $shortName->setStock(null);
            }
        }

        return $this;
    }

    public function getCreateAt(): ?DateTimeInterface
    {
        return $this->createAt;
    }

    public function setCreateAt(DateTimeInterface $createAt): self
    {
        $this->createAt = $createAt;

        return $this;
    }

    /**
     * @return Collection<int, UserStock>
     */
    public function getStocks(): Collection
    {
        return $this->stocks;
    }

    public function addStock(UserStock $stock): self
    {
        if (!$this->stocks->contains($stock)) {
            $this->stocks->add($stock);
            $stock->setStock($this);
        }

        return $this;
    }

    public function removeStock(UserStock $stock): self
    {
        if ($this->stocks->removeElement($stock)) {
            // set the owning side to null (unless already changed)
            if ($stock->getStock() === $this) {
                $stock->setStock(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Rate>
     */
    public function getRates(): Collection
    {
        return $this->rates;
    }

    public function addRate(Rate $rate): self
    {
        if (!$this->rates->contains($rate)) {
            $this->rates->add($rate);
            $rate->setStock($this);
        }

        return $this;
    }

    public function removeRate(Rate $rate): self
    {
        if ($this->rates->removeElement($rate)) {
            // set the owning side to null (unless already changed)
            if ($rate->getStock() === $this) {
                $rate->setStock(null);
            }
        }

        return $this;
    }
}
