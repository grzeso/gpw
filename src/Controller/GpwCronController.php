<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Services\DownloadFileFromUrl;
use App\Services\ReadDataFromExcel;
use App\Services\CreateExcelWithGpwData;
use Symfony\Component\HttpFoundation\Response;

class GpwCronController extends AbstractController
{
    /**
     * @Route("/cron/gpw", name="gpw_cron")
     */
    public function execute(DownloadFileFromUrl $download, ReadDataFromExcel $excel, CreateExcelWithGpwData $spreadsheet): Response
    {
        $filename = $download->downloadFile('https://www.gpw.pl/archiwum-notowan?fetch=1&type=10&instrument=&date=21-12-2020');

        $dataFromExcel = $excel->readDataFromFile($filename);

        $spreadsheet->create($dataFromExcel);

        return $this->render('gpw_cron/index.html.twig', [
        ]);
    }
}
