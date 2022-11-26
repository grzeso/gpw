<?php

namespace App\Service\Providers;

use App\Service\DataSource\ApiMoney;
use App\Service\ExcelBuilder\ExcelBuilder;
use App\Service\ExcelBuilder\ExcelBuilderByMoney;

class MoneyProvider extends AbstractProvider
{
    public const PROVIDER_NAME = 'MONEY';
    public const PROVIDER_ID = 1;
    private ApiMoney $apiMoney;
    protected ExcelBuilder $excelBuilder;

    public function __construct(
        ApiMoney $apiMoney,
        ExcelBuilderByMoney $excelBuilder
    ) {
        $this->apiMoney = $apiMoney;
        $this->excelBuilder = $excelBuilder;
    }

    public function execute(): void
    {
        $this->apiMoney->downloadDataByDate($this->date->format('Y-m-d'));
        $this->excelBuilder->setDataSource($this->apiMoney->getData());

        parent::execute();
    }
}
