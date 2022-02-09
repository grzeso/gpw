<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=UserRepository::class)
 */
class User
{
    public const USER_CRON = 4;
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=60)
     */
    private $name;

    /**
     * @ORM\ManyToMany(targetEntity=UsersEmails::class, mappedBy="user_id")
     */
    private $usersEmails;

    /**
     * @ORM\OneToMany(targetEntity=Log::class, mappedBy="user")
     */
    private $logs;

    public function __construct()
    {
        $this->usersEmails = new ArrayCollection();
        $this->logs = new ArrayCollection();
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
     * @return Collection|UsersEmails[]
     */
    public function getUsersEmails(): Collection
    {
        return $this->usersEmails;
    }

    public function addUsersEmail(UsersEmails $usersEmail): self
    {
        if (!$this->usersEmails->contains($usersEmail)) {
            $this->usersEmails[] = $usersEmail;
            $usersEmail->addUserId($this);
        }

        return $this;
    }

    public function removeUsersEmail(UsersEmails $usersEmail): self
    {
        if ($this->usersEmails->removeElement($usersEmail)) {
            $usersEmail->removeUserId($this);
        }

        return $this;
    }

    /**
     * @return Collection|Log[]
     */
    public function getLogs(): Collection
    {
        return $this->logs;
    }

    public function addLog(Log $log): self
    {
        if (!$this->logs->contains($log)) {
            $this->logs[] = $log;
            $log->setUser($this);
        }

        return $this;
    }

    public function removeLog(Log $log): self
    {
        if ($this->logs->removeElement($log)) {
            // set the owning side to null (unless already changed)
            if ($log->getUser() === $this) {
                $log->setUser(null);
            }
        }

        return $this;
    }
}
