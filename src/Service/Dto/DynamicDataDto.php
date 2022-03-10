<?php

namespace App\Service\Dto;

use DateTime;

class DynamicDataDto
{
    private string $date;

    public function setDate(DateTime $date): void
    {
        $this->date = $date->format('Y-m-d');
    }

    public function get(string $name): ?string
    {
        if (property_exists($this, $name)) {
            return $this->$name;
        }

        return null;
    }
}
