<?php

namespace App\Service\Providers;

use App\Entity\User\User;
use App\Service\Dto\DynamicDataDto;
use App\Service\ExcelBuilder\ExcelBuilder;
use DateTime;
use PhpOffice\PhpSpreadsheet\Exception;

abstract class AbstractProvider
{
    public const PROVIDER_NAME = '';
    protected User $user;
    protected DateTime $date;
    protected ExcelBuilder $excelBuilder;
    private DynamicDataDto $dynamicData;

    /**
     * @throws Exception
     */
    public function execute(): void
    {
        $this->excelBuilder->setUser($this->user);
        $this->excelBuilder->setDate($this->getDate());
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

    public function getDate(): DateTime
    {
        return $this->date;
    }

    public function setDynamicData(DynamicDataDto $dynamicDataDto): void
    {
        $this->dynamicData = $dynamicDataDto;
    }

    public function getAttachmentName(): string
    {
        return 'GPW_'.$this->getDate()->format('Y-m-d');
    }

    public function getBody(): string
    {
        return $this->excelBuilder->makeAttachement();
    }

    public function getType(): string
    {
        return 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet';
    }
}
