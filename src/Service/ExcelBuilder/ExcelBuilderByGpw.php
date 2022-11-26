<?php

namespace App\Service\ExcelBuilder;

use App\Dto\StockDto;
use App\Entity\User\UserStock;
use PhpOffice\PhpSpreadsheet\Reader\Exception;
use PhpOffice\PhpSpreadsheet\Reader\Xls;

class ExcelBuilderByGpw extends ExcelBuilder
{
    /**
     * @return array<int, StockDto>
     *
     * @throws Exception
     */
    protected function findUserStocks(): array
    {
        // excel z gpw
        $this->excelInput = (new Xls())->load($this->getDataSource());

        $activeSheet = $this->excelInput->getActiveSheet();
        $userStocks = $this->user->getUserStocks();

        $userStocksOutput = [];
        $highestRow = $activeSheet->getHighestRow();

        for ($row = 1; $row <= $highestRow; ++$row) {
            $name = $activeSheet->getCell('B'.$row);

            /* @var UserStock $userStock */
            foreach ($userStocks as $userStock) {
                if ($userStock->getStock()->getName() == $name->getValue()) {
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
