<?php

namespace App\Services;

use App\Entity\Stock;

class StocksServices
{
    public static function getStocksName(array $userStocks): array
    {
        return array_map(function ($stock) { return $stock->getName(); }, $userStocks);
    }

    public static function findUserStockValue($activeSheet, $userStocksName): array
    {
        $userStocks = [];
        $highestRow = $activeSheet->getHighestRow();

        for ($row = 1; $row <= $highestRow; ++$row) {
            if (in_array($activeSheet->getCell('B'.$row)->getValue(), $userStocksName)) {
                echo 'JEST  '.$activeSheet->getCell('B'.$row)
                 ->getValue().' KURS ZAMKNIECIA '.$activeSheet->getCell('H'.$row)->getValue();

                $stock = new Stock();
                $stock->setName($activeSheet->getCell('B'.$row)->getValue());
                $stock->setValue($activeSheet->getCell('H'.$row)->getValue());
                $stock->setChange($activeSheet->getCell('I'.$row)->getValue());
                array_push($userStocks, $stock);
            }
        }

        return $userStocks;
    }
}
