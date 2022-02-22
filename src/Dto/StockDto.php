<?php

namespace App\Dto;

class StockDto
{
    private string $name;
    private float $value;
    private string $change;
    private string $position;
    private int $quantity;

    public function getPosition(): string
    {
        return $this->position;
    }

    public function setPosition($position): void
    {
        $this->position = $position;
    }

    public function getChange(): string
    {
        return $this->change;
    }

    public function setChange($change)
    {
        $this->change = $change;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name)
    {
        $this->name = $name;
    }

    public function getValue(): float
    {
        return $this->value;
    }

    public function setValue(float $value)
    {
        $this->value = $value;
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
