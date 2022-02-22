<?php

namespace App\Service;

use App\Dto\StockDto;
use App\Entity\Stocks;
use App\Helper\Users\AbstractUser;
use App\Repository\StocksRepository;
use App\Service\SpecialFields\Dto\SpecialFieldsDto;
use PhpOffice\PhpSpreadsheet\Exception;
use PhpOffice\PhpSpreadsheet\Reader\Xls;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class ExcelBuilder
{
    private Spreadsheet $excelOutput;
    private StocksService $stocks;
    private Spreadsheet $excelInput;
    private StocksRepository $stocksRepository;
    private string $fileName;
    private AbstractUser $user;

    public function __construct(StocksRepository $stocksRepository)
    {
        $this->stocksRepository = $stocksRepository;
    }

    public function setStocks(StocksService $stocks): void
    {
        $this->stocks = $stocks;
    }

    public function setFileName(string $fileName): void
    {
        $this->fileName = $fileName;
    }

    public function getFileName(): string
    {
        return $this->fileName;
    }

    /**
     * @throws Exception
     */
    public function buildByGpwData()
    {
        // excel z gpw
        $this->excelInput = (new Xls())->load($this->getFileName());

        //mój excel
        $this->excelOutput = new Spreadsheet();
        $this->excelOutput->setActiveSheetIndex(0);

        $worksheet = $this->excelOutput->getActiveSheet();

        $sum = 0;

        /** @var StockDto $userStock */
        foreach ($this->findUserStocks() as $userStock) {
            $sum += $userStock->getQuantity() * $userStock->getValue();
            $worksheet
                ->setCellValue($userStock->getPosition().'1', $userStock->getName())
                ->setCellValue($userStock->getPosition().'2', $userStock->getValue())
                ->setCellValue($userStock->getPosition().'3', $userStock->getChange())
                ->setCellValue($userStock->getPosition().'4', $userStock->getQuantity())
                ->setCellValue($userStock->getPosition().'5', $userStock->getQuantity() * $userStock->getValue());
        }

        $worksheet->setCellValue('H8', 'WARTOSC:')->setCellValue('I8', $sum);
    }

    /**
     * @refactor - moze refactor
     *
     * @throws Exception
     */
    public function findUserStocks(): array
    {
        $activeSheet = $this->excelInput->getActiveSheet();
        $this->stocks->setUser($this->user);
        $userStocksName = $this->stocks->getUserStocksName();
        $userStocks = $this->stocks->getUserStocks();

        $userStocksOutput = [];
        $highestRow = $activeSheet->getHighestRow();

        for ($row = 1; $row <= $highestRow; ++$row) {
            $name = $activeSheet->getCell('B'.$row);
            if (in_array($name->getValue(), $userStocksName)) {
                $stock = new StockDto();
                $stock->setName($name->getValue());
                $stock->setValue($activeSheet->getCell('H'.$row)->getValue());
                $stock->setChange($activeSheet->getCell('I'.$row)->getValue());

                /** @var Stocks $userStock */
                foreach ($userStocks as $userStock) {
                    if ($userStock->getName() == $name->getValue()) {
                        $stock->setPosition($userStock->getPosition());
                        $stock->setQuantity($userStock->getQuantity());
                        break;
                    }
                }

                array_push($userStocksOutput, $stock);
            }
        }

        return $userStocksOutput;
    }

    public function makeFile()
    {
        $writer = new Xlsx($this->excelOutput);

        $writer->save('dane.xlsx');
    }

    public function makeAttachement(): string
    {
        ob_start();
        $writer = new Xlsx($this->excelOutput);
        $writer->save('php://output');
        $attachment = ob_get_contents();
        ob_end_clean();

        return $attachment;
    }

    public function setSpecialFields(SpecialFieldsDto $specialFieldsDto)
    {
        $data = $this->excelOutput->getActiveSheet();

        foreach ($specialFieldsDto->get() as $position => $value) {
            $data->setCellValue($position, $value);
        }
    }

    public function setUser(AbstractUser $user): void
    {
        $this->user = $user;
    }
}
