<?php

namespace App\Dto;

use App\Entity\Stock\Stock;
use DateTime;

class StockDto
{
    private Stock $stock;
    private float $rate;
    private float $change;
    private string $position;
    private int $quantity;
    private DateTime $rateDate;

    public function getRateDate(): DateTime
    {
        return $this->rateDate;
    }

    public function setRateDate(DateTime $rateDate): void
    {
        $this->rateDate = $rateDate;
    }

    public function getPosition(): string
    {
        return $this->position;
    }

    public function setPosition(?string $position): void
    {
        $this->position = $position;
    }

    public function getChange(): float
    {
        return $this->change;
    }

    public function setChange(float $change): void
    {
        $this->change = $change;
    }

    public function getStock(): Stock
    {
        return $this->stock;
    }

    public function setStock(Stock $stock): void
    {
        $this->stock = $stock;
    }

    public function getRate(): float
    {
        return $this->rate;
    }

    public function setRate(float $rate): void
    {
        $this->rate = $rate;
    }

    public function getQuantity(): int
    {
        return $this->quantity;
    }

    public function setQuantity(int $quantity): void
    {
        $this->quantity = $quantity;
    }
}
