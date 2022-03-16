<?php

namespace App\Entity;

use App\Repository\NameDictionaryRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=NameDictionaryRepository::class)
 */
class NameDictionary
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="integer")
     */
    private $provider;

    /**
     * @ORM\Column(type="string", length=100)
     */
    private $their_name;

    /**
     * @ORM\Column(type="string", length=100)
     */
    private $my_name;

    /**
     * @ORM\Column(type="datetime")
     */
    private $ts;

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

    public function getTheirName(): ?string
    {
        return $this->their_name;
    }

    public function setTheirName(string $their_name): self
    {
        $this->their_name = $their_name;

        return $this;
    }

    public function getMyName(): ?string
    {
        return $this->my_name;
    }

    public function setMyName(string $my_name): self
    {
        $this->my_name = $my_name;

        return $this;
    }

    public function getTs(): ?\DateTimeInterface
    {
        return $this->ts;
    }

    public function setTs(\DateTimeInterface $ts): self
    {
        $this->ts = $ts;

        return $this;
    }
}
