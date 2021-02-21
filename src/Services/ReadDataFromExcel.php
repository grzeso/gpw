<?php

namespace App\Services;

use PhpOffice\PhpSpreadsheet\Reader\Xls;
use PhpOffice\PhpSpreadsheet\Spreadsheet;

class ReadDataFromExcel
{
    public function load(string $fileName): Spreadsheet
    {
        return (new Xls())->load($fileName);
    }
}
