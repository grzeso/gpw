<?php

namespace App\Controller;

use Symfony\Component\Routing\Annotation\Route;
use App\Services\DownloadFileFromUrl;
use App\Services\ReadDataFromExcel;
use App\Entity\Stocks;
use App\Services\GpwSpreadsheet;
use App\Services\StocksServices;
use App\Services\CreateExcel;
use Swift_Attachment;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Exception;

class GpwCronController extends AbstractController
{
    /**
     * @Route("/cron/gpw", name="gpw_cron")
     */
    public function execute(DownloadFileFromUrl $download, ReadDataFromExcel $excel, GpwSpreadsheet $worksheet, \Swift_Mailer $mailer)
    {
        $currentDate = date('d-m-Y');
//        $currentDate = '20-02-2021';
        $userId = 1;
        $excludedDays = [6, 0];
        $name = 'GPW_'.$currentDate;

        //TODO REF - oddzielna klasa która to sprawdza + mozliwość wykluczenia konkretnych dat
        if (in_array(date('w'), $excludedDays)) {
            die('SOBOTA LUB NIEDZIELA');
        }

        $message = new \Swift_Message();
        $message->setFrom('gpw@grzegorzzdunczyk.pl');

        try {
            $filename = $download->downloadFile('https://www.gpw.pl/archiwum-notowan?fetch=1&type=10&instrument=&date='.$currentDate);
            $spreadsheet = $excel->load($filename);

            $worksheet->load($spreadsheet);
            $activeSheet = $worksheet->getActiveSheet();

            $userStocks = $this->getDoctrine()->getRepository(Stocks::class)->getUserStocks($userId);

            $userStocksName = StocksServices::getStocksName($userStocks);

            $value = StocksServices::findUserStockValue($activeSheet, $userStocksName);

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
                    ->setTo('grzeso@interia.pl')
                    ->setSubject($name)
                    ->setBody($name);

        $mailer->send($message);

        return $this->render('gpw_cron/index.html.twig');
    }
}
