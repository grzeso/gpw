<?php

namespace App\Service;

use App\Entity\Stocks;
use App\Entity\User;
use App\Repository\StocksRepository;

class StocksService
{
    /**
     * @var array<Stocks>
     */
    private array $userStocks;
    private User $user;
    private StocksRepository $stocksRepository;

    public function __construct(StocksRepository $stocksRepository)
    {
        $this->stocksRepository = $stocksRepository;
    }

    public function setUser(User $user): void
    {
        $this->user = $user;
    }

    public function findUserStocks(): void
    {
        $this->userStocks = $this->stocksRepository->getUserStocks($this->user->getId());
    }

    /**
     * @return array<Stocks>
     */
    public function getUserStocks(): ?array
    {
        return $this->userStocks;
    }

    /**
     * @return array<int, string|null>
     */
    public function getUserStocksName(): array
    {
        $this->findUserStocks();

        return array_map(function (Stocks $stock) { return $stock->getName(); }, $this->userStocks);
    }
}
