<?php

namespace App\Service\Providers;

use App\Service\ExcelBuilder;
use App\Service\File;
use App\Service\SpecialFields\Dto\SpecialFieldsDto;
use App\Service\StocksService;
use PhpOffice\PhpSpreadsheet\Exception;
use Swift_Attachment;

class GpwProvider extends AbstractProvider
{
    public const PROVIDER_NAME = 'GPW';
    private File $download;
    private StocksService $stocks;
    private SpecialFieldsDto $specialFieldsDto;
    private ExcelBuilder $excelBuilder;

    public function __construct(
        File $download,
        StocksService $stocks,
        SpecialFieldsDto $specialFieldsDto,
        ExcelBuilder $excelBuilder
    ) {
        $this->download = $download;
        $this->stocks = $stocks;
        $this->specialFieldsDto = $specialFieldsDto;
        $this->excelBuilder = $excelBuilder;
    }

    /**
     * @throws Exception
     */
    public function execute()
    {
        $this->download->downloadFileByDate($this->date->format('Y-m-d'));

        $this->excelBuilder->setFileName($this->download->getFileName());
        $this->excelBuilder->setUser($this->user);
        $this->excelBuilder->setStocks($this->stocks);
        $this->excelBuilder->buildByGpwData();

        $this->specialFieldsDto->setUser($this->user);
        $this->specialFieldsDto->setData($this->specialData);
        $this->excelBuilder->setSpecialFields($this->specialFieldsDto);
    }

    public function getAttachment(): Swift_Attachment
    {
        return new Swift_Attachment($this->excelBuilder->makeAttachement(), $this->getAttachmentName(), 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    }
}
