<?php

namespace App\Service\Providers;

use App\Entity\User;
use App\Service\Dto\DynamicDataDto;
use App\Service\ExcelBuilder\ExcelBuilder;
use App\Service\StocksService;
use DateTime;
use Swift_Attachment;

abstract class AbstractProvider
{
    public const PROVIDER_NAME = '';
    protected User $user;
    protected DateTime $date;
    protected ExcelBuilder $excelBuilder;
    protected StocksService $stocks;
    private DynamicDataDto $dynamicData;

    public function execute(): void
    {
        $this->excelBuilder->setUser($this->user);
        $this->excelBuilder->setStocks($this->stocks);
        $this->excelBuilder->setDynamicData($this->dynamicData);
        $this->excelBuilder->build();
    }

    public function check(string $provider): bool
    {
        return static::PROVIDER_NAME === $provider;
    }

    public function setUser(User $user): void
    {
        $this->user = $user;
    }

    public function setDate(DateTime $date): void
    {
        $this->date = $date;
    }

    public function setDynamicData(DynamicDataDto $dynamicDataDto): void
    {
        $this->dynamicData = $dynamicDataDto;
    }

    public function getAttachmentName(): string
    {
        return 'GPW_'.$this->date->format('Y-m-d');
    }

    public function getAttachment(): Swift_Attachment
    {
        return new Swift_Attachment($this->excelBuilder->makeAttachement(), $this->getAttachmentName(), 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    }
}
