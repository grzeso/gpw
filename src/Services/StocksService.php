<?php

namespace App\Services;

use App\Entity\Stocks;
use Doctrine\ORM\EntityManagerInterface;

class StocksService
{
    /**
     * @var EntityManager
     */
    private $entityManager;
    private $userStocks;
    private $userId;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function setUserId(int $userId): void
    {
        $this->userId = $userId;
    }

    public function findUserStocks()
    {
        $this->userStocks = $this->entityManager->getRepository(Stocks::class)->getUserStocks($this->userId);
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
