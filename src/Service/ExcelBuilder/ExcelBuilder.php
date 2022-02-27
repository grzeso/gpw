<?php

namespace App\Service\ExcelBuilder;

use App\Dto\StockDto;
use App\Helper\Users\AbstractUser;
use App\Repository\StocksRepository;
use App\Service\SpecialFields\Dto\SpecialFieldsDto;
use App\Service\StocksService;
use PhpOffice\PhpSpreadsheet\Exception;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

abstract class ExcelBuilder
{
    private Spreadsheet $excelOutput;
    protected StocksService $stocks;
    protected Spreadsheet $excelInput;
    private StocksRepository $stocksRepository;
    private string $dataSource;
    protected AbstractUser $user;

    public function __construct(StocksRepository $stocksRepository)
    {
        $this->stocksRepository = $stocksRepository;
    }

    abstract public function findUserStocks(): array;

    public function setStocks(StocksService $stocks): void
    {
        $this->stocks = $stocks;
    }

    public function setDataSource(string $dataSource): void
    {
        $this->dataSource = $dataSource;
    }

    public function getDataSource(): string
    {
        return $this->dataSource;
    }

    /**
     * @throws Exception
     */
    public function build()
    {
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
