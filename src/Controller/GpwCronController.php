<?php

namespace App\Controller;

use App\Entity\Stocks;
use App\Helper\DaysWithoutSessionHelper;
use App\Helper\UserHelper;
use App\Services\CreateExcel;
use App\Services\DownloadFileFromUrl;
use App\Services\GpwSpreadsheet;
use App\Services\ReadDataFromExcel;
use App\Services\StocksServices;
use Exception;
use Swift_Attachment;
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
            ReadDataFromExcel $excel,
            GpwSpreadsheet $worksheet,
            Sift_Mailer $mailer,
            DaysWithoutSessionHelper $dwss,
            UserHelper $userHelper
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
            // ref - dać to do jednej klasy
            $filename = $download->downloadFile('https://www.gpw.pl/archiwum-notowan?fetch=1&type=10&instrument=&date='.$date);
            $spreadsheet = $excel->load($filename);

            $worksheet->load($spreadsheet);
            $activeSheet = $worksheet->getActiveSheet();

            $userStocks = $this->getDoctrine()->getRepository(Stocks::class)->getUserStocks($userId);

            $userStocksName = StocksServices::getStocksName($userStocks);

            $value = StocksServices::findUserStockValue($activeSheet, $userStocksName);

            //ref end

            $outputExcel = new CreateExcel();
            $outputExcel->setUserStocks($userStocks);
            $outputExcel->setValue($value);
            $outputExcel->create();
            $outputExcel->addSpecialFields($userId);

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
