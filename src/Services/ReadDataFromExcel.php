<?php

namespace App\Services;

use PhpOffice\PhpSpreadsheet\Reader\Xls;
use PhpOffice\PhpSpreadsheet\Spreadsheet;

class ReadDataFromExcel
{
    public function readDataFromFile(string $fileName): Spreadsheet
    {
        $reader = new Xls();

        return $reader->load($fileName);
    }
}
