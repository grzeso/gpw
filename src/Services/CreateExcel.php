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

        dump($this->excel);

        $allValue = 0;

        foreach ($this->userStocks as $userStock) {
            foreach ($this->value as $value) {
                if ($value->getName() === $userStock->getName()) {
                    echo $value->getName();

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

        $data->setCellValue('H5', 'WARTOSC:')->setCellValue('I5', $allValue);
    }

    public function makeFile()
    {
        $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($this->excel);

        $writer->save('dane.xlsx');

//        header('Content-Type: application/vnd.ms-excel');
//header('Content-Disposition: attachment; filename="file.xls"');
//$writer->save("php://output");
    }

    public function makeAttachement()
    {
        ob_start();
        $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($this->excel);
        $writer->save('php://output');
        $attachment = ob_get_contents();
        ob_end_clean();

        return $attachment;
//        $writer->save('dane.xlsx');

//        header('Content-Type: application/vnd.ms-excel');
//header('Content-Disposition: attachment; filename="file.xls"');
//$writer->save("php://output");
    }
}
