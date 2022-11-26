<?php

namespace App\EventSubscriber\StockDto;

use App\Entity\Stock\Rate;
use App\Event\StockDto\StockDtoEvent;
use App\Repository\Stock\RateRepository;
use DateTime;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class StockDtoSubscriber implements EventSubscriberInterface
{
    public function __construct(private RateRepository $rateRepository)
    {
    }

    public static function getSubscribedEvents()
    {
        return [
            StockDtoEvent::SAVE_RATE => 'save',
        ];
    }

    public function save(StockDtoEvent $stockDtoEvent): void
    {
        $stockDto = $stockDtoEvent->getEntity();

        $rate = $this->rateRepository->findOneBy([
            'stock' => $stockDto->getStock(),
            'rateDate' => $stockDto->getRateDate(),
        ]);

        if (null !== $rate) {
            return;
        }

        $rate = (new Rate())
            ->setStock($stockDto->getStock())
            ->setRate($stockDto->getRate())
            ->setRateDate($stockDto->getRateDate())
            ->setCreatedAt(new DateTime());

        $this->rateRepository->save($rate, true);
    }
}
