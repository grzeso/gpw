<?php

namespace App\Services;

use App\Entity\Stocks;
use App\Helper\Users\AbstractUser;
use Doctrine\ORM\EntityManagerInterface;

class StocksService
{
    private $entityManager;
    private $userStocks;
    private AbstractUser $user;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function setUser(AbstractUser $user): void
    {
        $this->user = $user;
    }

    public function findUserStocks()
    {
        $this->userStocks = $this->entityManager->getRepository(Stocks::class)->getUserStocks($this->user->getUserId());
    }

    public function getUserStocks()
    {
        return $this->userStocks;
    }

    public function getUserStocksName(): array
    {
        $this->findUserStocks();

        return array_map(function ($stock) { return $stock->getName(); }, $this->userStocks);
    }
}
