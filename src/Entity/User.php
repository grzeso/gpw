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

    public function __construct()
    {
        $this->usersEmails = new ArrayCollection();
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
}
