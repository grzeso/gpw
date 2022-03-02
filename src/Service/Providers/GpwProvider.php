<?php

namespace App\Service\Providers;

use App\Service\DataSource\GpwFile;
use App\Service\ExcelBuilder\ExcelBuilder;
use App\Service\ExcelBuilder\ExcelBuilderByGpw;
use App\Service\StocksService;

class GpwProvider extends AbstractProvider
{
    public const PROVIDER_NAME = 'GPW';
    private GpwFile $download;
    protected StocksService $stocks;
    protected ExcelBuilder $excelBuilder;

    public function __construct(
        GpwFile $download,
        StocksService $stocks,
        ExcelBuilderByGpw $excelBuilder
    ) {
        $this->download = $download;
        $this->stocks = $stocks;
        $this->excelBuilder = $excelBuilder;
    }

    public function execute()
    {
        $this->download->downloadFileByDate($this->date->format('Y-m-d'));
        $this->excelBuilder->setDataSource($this->download->getFileName());

        parent::execute();
    }
}
