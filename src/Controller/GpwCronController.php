<?php

namespace App\Controller;

use App\Helper\DaysWithoutSessionHelper;
use App\Helper\UserHelper;
use App\Services\CreateExcel;
use App\Services\DownloadFileFromUrl;
use App\Services\Excel;
use App\Services\GpwExcel;
use App\Services\StocksService;
use Exception;
use Swift_Attachment;
use Swift_Mailer;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class GpwCronController extends AbstractController
{
    /**
     * @Route("/cron/gpw/{date?}", name="gpw_cron")
     */
    public function execute(
            $date,
            DownloadFileFromUrl $download,
            Swift_Mailer $mailer,
            DaysWithoutSessionHelper $dwss,
            UserHelper $userHelper,
            Excel $excel,
            StocksService $stocks,
            GpwExcel $gpwExcel
            ) {
        if (!$date) {
            $date = date('d-m-Y');

            if ($dwss::isDayWithoutSession()) {
                exit('Dzień bez sesji');
            }
        }

        $userId = 1;

        $name = 'GPW_'.$date;

        $message = new \Swift_Message();
        $message->setFrom($this->getParameter('gpw_email'));

        try {
            $filename = $download->downloadFile('https://www.gpw.pl/archiwum-notowan?fetch=1&type=10&instrument=&date='.$date);

            $excel->loadFile($filename);
            $stocks->setUserId($userId);

            $gpwExcel->setExcel($excel);
            $gpwExcel->setStocks($stocks);

            $outputExcel = new CreateExcel();
            $outputExcel->setUserId($userId);
            $outputExcel->setStocks($stocks);
            $outputExcel->setGpwExcel($gpwExcel);
            $outputExcel->create();
            $outputExcel->addSpecialFields();

            $attachment = new Swift_Attachment($outputExcel->makeAttachement(), $name, 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');

            $message
                   ->attach($attachment);
        } catch (PhpOffice\PhpSpreadsheet\Exception $e) {
            $name = $e->getMessage();
        } catch (Exception $e) {
            $name = $e->getMessage();
        }
        $message
                    ->setTo($userHelper::getUserMails($userId))
                    ->setSubject($name)
                    ->setBody($name);

        $mailer->send($message);

        return $this->render('gpw_cron/index.html.twig');
    }
}
