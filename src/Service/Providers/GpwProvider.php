<?php

namespace App\Service\Providers;

use App\Service\DataSource\GpwFile;
use App\Service\ExcelBuilder\ExcelBuilder;
use App\Service\ExcelBuilder\ExcelBuilderByGpw;

class GpwProvider extends AbstractProvider
{
    public const PROVIDER_NAME = 'GPW';
    private GpwFile $download;
    protected ExcelBuilder $excelBuilder;

    public function __construct(
        GpwFile $download,
        ExcelBuilderByGpw $excelBuilder
    ) {
        $this->download = $download;
        $this->excelBuilder = $excelBuilder;
    }

    public function execute(): void
    {
        $this->download->downloadFileByDate($this->date->format('Y-m-d'));
        $this->excelBuilder->setDataSource($this->download->getFileName());

        parent::execute();
    }
}
