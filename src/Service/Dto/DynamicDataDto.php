<?php

namespace App\Service\Dto;

class DynamicDataDto
{
    private string $date;

    public function setDate(string $date): void
    {
        $this->date = $date;
    }

    public function get(string $name): ?string
    {
        if (property_exists($this, $name)) {
            return $this->$name;
        }

        return null;
    }
}
