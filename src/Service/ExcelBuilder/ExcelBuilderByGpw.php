<?php

namespace App\Service\ExcelBuilder;

use App\Dto\StockDto;
use App\Entity\Stocks;
use PhpOffice\PhpSpreadsheet\Exception;
use PhpOffice\PhpSpreadsheet\Reader\Xls;

class ExcelBuilderByGpw extends ExcelBuilder
{
    /**
     * @refactor - moze refactor
     *
     * @throws Exception
     */
    protected function findUserStocks(): array
    {
        // excel z gpw
        $this->excelInput = (new Xls())->load($this->getDataSource());

        $activeSheet = $this->excelInput->getActiveSheet();
        $this->stocks->setUser($this->user);
        $userStocksName = $this->stocks->getUserStocksName();
        $userStocks = $this->stocks->getUserStocks();

        $userStocksOutput = [];
        $highestRow = $activeSheet->getHighestRow();

        for ($row = 1; $row <= $highestRow; ++$row) {
            $name = $activeSheet->getCell('B'.$row);

            /* @var Stocks $userStock */
            foreach ($userStocks as $userStock) {
                if (in_array($name->getValue(), $userStocksName) && $userStock->getName() == $name->getValue()) {
                    $stock = new StockDto();
                    $stock->setName($name->getValue());
                    $stock->setValue($activeSheet->getCell('H'.$row)->getValue());
                    $stock->setChange($activeSheet->getCell('I'.$row)->getValue());
                    $stock->setPosition($userStock->getPosition());
                    $stock->setQuantity($userStock->getQuantity());
                    array_push($userStocksOutput, $stock);
                }
            }
        }

        return $userStocksOutput;
    }
}
