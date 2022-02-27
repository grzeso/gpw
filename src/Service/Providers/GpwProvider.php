<?php

namespace App\Service\Providers;

use App\Service\DataSource\GpwFile;
use App\Service\ExcelBuilder\ExcelBuilder;
use App\Service\ExcelBuilder\ExcelBuilderByGpw;
use App\Service\SpecialFields\Dto\SpecialFieldsDto;
use App\Service\StocksService;

class GpwProvider extends AbstractProvider
{
    public const PROVIDER_NAME = 'GPW';
    private GpwFile $download;
    protected StocksService $stocks;
    protected SpecialFieldsDto $specialFieldsDto;
    protected ExcelBuilder $excelBuilder;

    public function __construct(
        GpwFile $download,
        StocksService $stocks,
        SpecialFieldsDto $specialFieldsDto,
        ExcelBuilderByGpw $excelBuilder
    ) {
        $this->download = $download;
        $this->stocks = $stocks;
        $this->specialFieldsDto = $specialFieldsDto;
        $this->excelBuilder = $excelBuilder;
    }

    public function execute()
    {
        $this->download->downloadFileByDate($this->date->format('Y-m-d'));
        $this->excelBuilder->setDataSource($this->download->getFileName());

        parent::execute();
    }
}
