<?php

namespace App\Service\ExcelBuilder;

use App\Dto\StockDto;
use App\Entity\User\User;
use App\Repository\Provider\ShortNameRepository;
use App\Service\Dto\DynamicDataDto;
use DateTime;
use PhpOffice\PhpSpreadsheet\Exception;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

abstract class ExcelBuilder
{
    private Spreadsheet $excelOutput;
    protected Spreadsheet $excelInput;
    private string $dataSource;
    protected User $user;
    protected DateTime $date;
    private string $devInfo;
    private DynamicDataDto $dynamicData;
    protected ShortNameRepository $nameDictionaryRepository;

    public function __construct(string $devInfo, ShortNameRepository $nameDictionaryRepository)
    {
        $this->devInfo = $devInfo;
        $this->nameDictionaryRepository = $nameDictionaryRepository;
    }

    /**
     * @return array<int, StockDto>
     */
    abstract protected function findUserStocks(): array;

    public function setDataSource(string $dataSource): void
    {
        $this->dataSource = $dataSource;
    }

    public function setDynamicData(DynamicDataDto $dynamicData): void
    {
        $this->dynamicData = $dynamicData;
    }

    public function getDataSource(): string
    {
        return $this->dataSource;
    }

    public function getDate(): DateTime
    {
        return $this->date;
    }

    public function setDate(DateTime $date): void
    {
        $this->date = $date;
    }

    /**
     * @throws Exception
     */
    public function build(): void
    {
        // mój excel
        $this->excelOutput = new Spreadsheet();
        $this->excelOutput->setActiveSheetIndex(0);

        $worksheet = $this->excelOutput->getActiveSheet();
        $worksheet->setTitle($this->getDate()->format('Y-m-d'));

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
        $worksheet->setCellValue('H10', $this->devInfo);

        $this->setDefinedFields();
        $this->setDynamicFields();
    }

    public function makeFile(): void
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

    private function setDefinedFields(): void
    {
        $excelOutput = $this->excelOutput->getActiveSheet();
        foreach (($this->user->getDefinedFields()) as $position => $definedField) {
            $excelOutput->setCellValue($position, $definedField);
        }
    }

    private function setDynamicFields(): void
    {
        $excelOutput = $this->excelOutput->getActiveSheet();
        foreach ($this->user->getDynamicFields() as $position => $dynamicField) {
            $excelOutput->setCellValue($position, $this->dynamicData->get($dynamicField));
        }
    }

    public function setUser(User $user): void
    {
        $this->user = $user;
    }
}
