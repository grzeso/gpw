<?php

namespace App\Services;

use PhpOffice\PhpSpreadsheet\Spreadsheet;

class CreateExcelWithGpwData
{
    public function create(Spreadsheet $spreadsheet)
    {
        $workSheet = $spreadsheet->getActiveSheet();
        $cellH327 = $workSheet->getCell('H328')->getValue();

        var_dump($cellH327);
    }
}
