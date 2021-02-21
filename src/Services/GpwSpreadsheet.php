<?php

namespace App\Services;

use PhpOffice\PhpSpreadsheet\Spreadsheet;

class GpwSpreadsheet
{
    private $spreadsheet;

    public function load(Spreadsheet $spreadsheet)
    {
        $this->spreadsheet = $spreadsheet;
    }

    public function getActiveSheet()
    {
        return $this->spreadsheet->getActiveSheet();
    }
}
