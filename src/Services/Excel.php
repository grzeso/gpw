<?php

namespace App\Services;

use PhpOffice\PhpSpreadsheet\Reader\Xls;

class Excel
{
    private $spreadsheet;

    public function loadFile(string $fileName): void
    {
        $this->spreadsheet = (new Xls())->load($fileName);
    }

    public function getActiveExcel()
    {
        return $this->spreadsheet->getActiveSheet();
    }
}
