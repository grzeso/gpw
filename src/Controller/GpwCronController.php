<?php

namespace App\Controller;

use App\Entity\User;
use App\Service\Access\AccessService;
use App\Service\Dto\DynamicDataDto;
use App\Service\Logger\Logger;
use App\Service\Providers\ProviderFactory;
use App\Service\Settings\SettingsService;
use App\Service\UserEmailsService;
use DateTime;
use Exception;
use Swift_Mailer;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class GpwCronController extends AbstractController
{
    /**
     * @Route("/cron/gpw/{id}/{originalDate?}/{allowed?}", name="gpw_cron",methods={"GET"})
     */
    public function execute(
        int $id,
        ?string $originalDate,
        ?int $allowed,
        User $user,
        Swift_Mailer $mailer,
        Logger $logger,
        ProviderFactory $providerFactory,
        DynamicDataDto $dynamicDataDto,
        UserEmailsService $userEmailsService,
        AccessService $accessService,
        SettingsService $settingsService
    ): Response {
        if (!$date = DateTime::createFromFormat('d-m-Y', $originalDate)) {
            $date = new DateTime();
        }
        $settingsService->updateLogNumber();

        $logger->setUser($user);
        $logger->setLogId($settingsService->getLogNumber());
        $logger->setDate($date);
        $logger->logStart($id, $originalDate ?? '');

        $message = $mailer->createMessage();

        try {
            $accessService->setLogger($logger);
            $accessService->isAccess($date, $allowed);
            $dynamicDataDto->setDate($date);

            $provider = $providerFactory->getProvider($this->getParameter('gpw.provider'));
            $provider->setUser($user);
            $provider->setDate($date);
            $provider->setDynamicData($dynamicDataDto);
            $provider->execute();

            $name = 'GPW_'.$date->format('Y-m-d');

            $message->attach($provider->getAttachment());
        } catch (Exception $e) {
            $name = $e->getMessage();
            $logger->logError($e);
        }

        $message
            ->setFrom($this->getParameter('gpw.email'))
            ->setTo($userEmailsService->convert($user))
            ->setSubject($name)
            ->setBody($name);

        $result = $mailer->send($message);

        $logger->logSent($result);

        return $this->render('gpw_cron/index.html.twig');
    }
}
