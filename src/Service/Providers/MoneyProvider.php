<?php

namespace App\Service\Providers;

use App\Service\DataSource\ApiMoney;
use App\Service\ExcelBuilder\ExcelBuilder;
use App\Service\ExcelBuilder\ExcelBuilderByMoney;
use App\Service\StocksService;

class MoneyProvider extends AbstractProvider
{
    const PROVIDER_NAME = 'MONEY';
    private ApiMoney $apiMoney;
    protected StocksService $stocks;
    protected ExcelBuilder $excelBuilder;

    public function __construct(
        ApiMoney $apiMoney,
        StocksService $stocks,
        ExcelBuilderByMoney $excelBuilder
    ) {
        $this->apiMoney = $apiMoney;
        $this->stocks = $stocks;
        $this->excelBuilder = $excelBuilder;
    }

    public function execute(): void
    {
        $this->apiMoney->downloadDataByDate($this->date->format('Y-m-d'));
        $this->excelBuilder->setDataSource($this->apiMoney->getData());

        parent::execute();
    }
}
