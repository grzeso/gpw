<?php

namespace App\Services;

use App\Services\SpecialFields\Dto\SpecialFieldsDto;
use PhpOffice\PhpSpreadsheet\Spreadsheet;

class CreateExcel
{
    private $excel;
    private $stocks;
    private $gpwExcel;

    private string $date;

    public function setStocks($stocks)
    {
        $this->stocks = $stocks;
    }

    public function setGpwExcel($gpwExcel)
    {
        $this->gpwExcel = $gpwExcel;
    }

    public function create()
    {
        $this->excel = new Spreadsheet();
        $this->excel->setActiveSheetIndex(0);

        $data = $this->excel->getActiveSheet();

        $allValue = 0;

        $this->stocks->findUserStocks();

        foreach ($this->stocks->getUserStocks() as $userStock) {
            foreach ($this->gpwExcel->findStockValue() as $value) {
                if ($value->getName() === $userStock->getName()) {
                    $allValue += $userStock->getQuantity() * $value->getValue();

                    $data
                            ->setCellValue($userStock->getPosition().'1', $userStock->getName())
                            ->setCellValue($userStock->getPosition().'2', $value->getValue())
                            ->setCellValue($userStock->getPosition().'3', $value->getChange())
                            ->setCellValue($userStock->getPosition().'4', $userStock->getQuantity())
                            ->setCellValue($userStock->getPosition().'5', $userStock->getQuantity() * $value->getValue());
                }
            }
        }

        $data->setCellValue('H8', 'WARTOSC:')->setCellValue('I8', $allValue);
    }

    public function makeFile()
    {
        $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($this->excel);

        $writer->save('dane.xlsx');
    }

    public function makeAttachement(): string
    {
        ob_start();
        $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($this->excel);
        $writer->save('php://output');
        $attachment = ob_get_contents();
        ob_end_clean();

        return $attachment;
    }

    public function setSpecialFields(SpecialFieldsDto $specialFieldsDto)
    {
        $data = $this->excel->getActiveSheet();

        foreach ($specialFieldsDto->get() as $position => $value) {
            $data->setCellValue($position, $value);
        }
    }
}
