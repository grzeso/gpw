<?php

namespace App\Services;

use App\Entity\Stock;

class GpwExcel
{
    private $excel;
    private $stocks;

    public function setExcel($excel)
    {
        $this->excel = $excel;
    }

    public function setStocks($stocks)
    {
        $this->stocks = $stocks;
    }

    public function findStockValue(): array
    {
        $activeSheet = $this->excel->getActiveExcel();
        $userStocksName = $this->stocks->getUserStocksName();

        $userStocks = [];
        $highestRow = $activeSheet->getHighestRow();

        for ($row = 1; $row <= $highestRow; ++$row) {
            if (in_array($activeSheet->getCell('B'.$row)->getValue(), $userStocksName)) {
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
