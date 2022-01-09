<?php

namespace App\Controller;

use App\Helper\DaysWithoutSessionHelper;
use App\Helper\UserHelper;
use App\Helper\Users\UsersFactory;
use App\Services\CreateExcel;
use App\Services\DownloadFileFromUrl;
use App\Services\Excel;
use App\Services\GpwExcel;
use App\Services\SpecialFields\Dto\SpecialFieldsDto;
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
            ?string $date,
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

        $specialData = [
            'date' => date('Y-m-d', strtotime($date)),
        ];

        $userId = 1;

        $name = 'GPW_'.$date;

        $message = new \Swift_Message();
        $message->setFrom($this->getParameter('gpw_email'));

        try {
            $user = (new UsersFactory())->factory($userId);
            $excel->loadFile($download->fasade($date));
            $stocks->setUser($user);

            $gpwExcel->setExcel($excel);
            $gpwExcel->setStocks($stocks);

            $outputExcel = new CreateExcel();
            $outputExcel->setStocks($stocks);
            $outputExcel->setGpwExcel($gpwExcel);
            $outputExcel->create();
            $specialFieldsDto = (new SpecialFieldsDto($user, $specialData));
            $outputExcel->setSpecialFields($specialFieldsDto);

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
