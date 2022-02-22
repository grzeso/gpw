<?php

namespace App\Service;

use App\Helper\Users\AbstractUser;
use App\Repository\StocksRepository;

class StocksService
{
    private ?array $userStocks;
    private AbstractUser $user;
    private StocksRepository $stocksRepository;

    public function __construct(StocksRepository $stocksRepository)
    {
        $this->stocksRepository = $stocksRepository;
    }

    public function setUser(AbstractUser $user): void
    {
        $this->user = $user;
    }

    public function findUserStocks()
    {
        $this->userStocks = $this->stocksRepository->getUserStocks($this->user->getUserId());
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
