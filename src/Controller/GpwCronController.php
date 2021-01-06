<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Services\DownloadFileFromUrl;
use Symfony\Component\HttpFoundation\Response;

class GpwCronController extends AbstractController
{
    /**
     * @Route("/cron/gpw", name="gpw_cron")
     */
    public function execute(DownloadFileFromUrl $download): Response
    {
//        echo '<pre>';
//        var_dump($download);

        $excel = $download->downloadFile('https://www.gpw.pl/archiwum-notowan?fetch=1&type=10&instrument=&date=28-12-2020');

        var_dump($excel);

        return $this->render('gpw_cron/index.html.twig', [
        ]);
    }
}
