<?php

namespace App\Service\Providers;

use App\Service\DataSource\ApiMoney;
use App\Service\ExcelBuilder\ExcelBuilder;
use App\Service\ExcelBuilder\ExcelBuilderByMoney;
use App\Service\SpecialFields\Dto\SpecialFieldsDto;
use App\Service\StocksService;

class MoneyProvider extends AbstractProvider
{
    const PROVIDER_NAME = 'MONEY';
    private ApiMoney $apiMoney;
    protected StocksService $stocks;
    protected SpecialFieldsDto $specialFieldsDto;
    protected ExcelBuilder $excelBuilder;

    public function __construct(
        ApiMoney $apiMoney,
        StocksService $stocks,
        SpecialFieldsDto $specialFieldsDto,
        ExcelBuilderByMoney $excelBuilder
    ) {
        $this->apiMoney = $apiMoney;
        $this->stocks = $stocks;
        $this->specialFieldsDto = $specialFieldsDto;
        $this->excelBuilder = $excelBuilder;
    }

    public function execute()
    {
        $this->apiMoney->downloadDataByDate($this->date->format('Y-m-d'));
        $this->excelBuilder->setDataSource($this->apiMoney->getData());

        parent::execute();
    }
}