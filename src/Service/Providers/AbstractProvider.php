<?php

namespace App\Service\Providers;

use App\Helper\Users\AbstractUser;
use App\Service\ExcelBuilder\ExcelBuilder;
use App\Service\SpecialFields\Dto\SpecialFieldsDto;
use App\Service\StocksService;
use DateTime;
use Swift_Attachment;

abstract class AbstractProvider
{
    protected AbstractUser $user;
    protected DateTime $date;
    protected array $specialData;
    protected ExcelBuilder $excelBuilder;
    protected StocksService $stocks;
    protected SpecialFieldsDto $specialFieldsDto;

    public function execute()
    {
        $this->excelBuilder->setUser($this->user);
        $this->excelBuilder->setStocks($this->stocks);
        $this->excelBuilder->build();

        $this->specialFieldsDto->setUser($this->user);
        $this->specialFieldsDto->setData($this->specialData);
        $this->excelBuilder->setSpecialFields($this->specialFieldsDto);
    }

    public function setUser(AbstractUser $user): void
    {
        $this->user = $user;
    }

    public function setDate(DateTime $date)
    {
        $this->date = $date;
    }

    public function setSpecialData(array $specialData)
    {
        $this->specialData = $specialData;
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
