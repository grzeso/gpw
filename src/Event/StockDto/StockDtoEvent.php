<?php

namespace App\Event\StockDto;

use App\Dto\StockDto;
use Symfony\Contracts\EventDispatcher\Event;

class StockDtoEvent extends Event
{
    public const SAVE_RATE = 'saveRate';

    private StockDto $entity;

    public function __construct(StockDto $entity)
    {
        $this->entity = $entity;
    }

    public function getEntity(): StockDto
    {
        return $this->entity;
    }
}
