<?php

namespace App\Services;

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Reader\Xlsx;

class CreateExcel
{
    private $excel;
    private $userStocks;
    private $value;

    public function getUserStocks()
    {
        return $this->userStocks;
    }

    public function getValue()
    {
        return $this->value;
    }

    public function setUserStocks($userStocks)
    {
        $this->userStocks = $userStocks;
    }

    public function setValue($value)
    {
        $this->value = $value;
    }

    public function create()
    {
        $this->excel = new Spreadsheet();
        $this->excel->setActiveSheetIndex(0);

        $data = $this->excel->getActiveSheet();

        $allValue = 0;

        foreach ($this->userStocks as $userStock) {
            foreach ($this->value as $value) {
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

    public function addSpecialFields($userId)
    {
        $specialFields = (new SpecialFields\SpecialFieldsFactory())->factory($userId);
        $data = $this->excel->getActiveSheet();

        foreach ($specialFields as $position => $value) {
            $data->setCellValue($position, $value);
        }
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
}
