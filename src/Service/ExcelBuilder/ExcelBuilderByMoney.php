<?php

namespace App\Service\ExcelBuilder;

use App\Dto\StockDto;
use App\Entity\Provider\ShortName;
use App\Entity\User\UserStock;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Criteria;

class ExcelBuilderByMoney extends ExcelBuilder
{
    /**
     * @return array<int, StockDto>
     */
    protected function findUserStocks(): array
    {
        $userStocks = $this->user->getUserStocks();
        $dataSource = json_decode($this->getDataSource())->data->data;
        $collection = new ArrayCollection($dataSource);

        $userStocksOutput = [];
        /** @var UserStock $userStock */
        foreach ($userStocks as $userStock) {
            $moneyStock = null;

            $names = $userStock->getStock()->getShortNames();
            /** @var ShortName $name */
            foreach ($names as $name) {
                $criteria = Criteria::create()
                    ->andWhere(Criteria::expr()->eq('symbol', $name->getNameInProvider()))
                    ->orWhere(Criteria::expr()->eq('nazwaPelna', $name->getNameInProvider()));
                $moneyStock = $collection->matching($criteria)->first();
            }

            if (!$moneyStock) {
                continue;
            }

            $stock = new StockDto();
            $stock->setName($userStock->getStock()->getName());
            $stock->setValue($moneyStock->kurs);
            $stock->setChange(round($moneyStock->changePrev, 2));
            $stock->setPosition($userStock->getPosition());
            $stock->setQuantity($userStock->getQuantity());
            $userStocksOutput[] = $stock;
        }

        return $userStocksOutput;
    }
}
