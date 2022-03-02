<?php

namespace App\Service;

use App\Entity\User;
use App\Repository\StocksRepository;

class StocksService
{
    private ?array $userStocks;
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

    public function findUserStocks()
    {
        $this->userStocks = $this->stocksRepository->getUserStocks($this->user->getId());
    }

    public function getUserStocks(): ?array
    {
        return $this->userStocks;
    }

    public function getUserStocksName(): array
    {
        $this->findUserStocks();

        return array_map(function ($stock) { return $stock->getName(); }, $this->userStocks);
    }
}
