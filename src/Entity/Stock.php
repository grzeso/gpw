<?php

namespace App\Entity;

class Stock
{
    private $name;
    private $value;
    private $change;

    public function getChange()
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

    public function getValue(): float
    {
        return $this->value;
    }

    public function setName(string $name)
    {
        $this->name = $name;
    }

    public function setValue(float $value)
    {
        $this->value = $value;
    }
}
