<?php

namespace App\Controller;

use App\Entity\Settings;
use App\Entity\User;
use App\Helper\SettingsHelper;
use App\Service\Access\AccessService;
use App\Service\Dto\DynamicDataDto;
use App\Service\Logger\Logger;
use App\Service\Providers\MoneyProvider;
use App\Service\Providers\ProviderFactory;
use App\Service\UserEmailsService;
use DateTime;
use Exception;
use Swift_Mailer;
use Swift_Message;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class GpwCronController extends AbstractController
{
    /**
     * @Route("/cron/gpw/{userId}/{date?}/{allowed?}", name="gpw_cron")
     */
    public function execute(
        int $userId,
        ?string $date,
        ?int $allowed,
        Swift_Mailer $mailer,
        Logger $logger,
        SettingsHelper $settingsHelper,
        ProviderFactory $providerFactory,
        DynamicDataDto $dynamicDataDto,
        UserEmailsService $userEmailsService,
        AccessService $accessService
    ) {
        if (!$userId) {
            $userId = User::USER_CRON;
        }
        /** @var User $user */
        $user = $this->getDoctrine()->getRepository(User::class)->find($userId);
        /** @var Settings $logNumber */
        $logNumber = $this->getDoctrine()->getRepository(Settings::class)->findOneBy(['name' => 'log_number']);
        $settingsHelper->updateLogNumber($logNumber);

        $originalDate = $date;
        if (!$date = DateTime::createFromFormat('d-m-Y', $date)) {
            $date = new DateTime();
        }

        $logger->log(Logger::EVENT_START_MESSAGE, $user, Logger::EVENT_START, (int) $logNumber->getValue(), $date->format('d-m-Y'), ['userId' => $userId, 'date' => $originalDate ?? '']);

        try {
            $accessService->isAccess($date->format('d-m-Y'), $allowed);
        } catch (Exception $e) {
            $logger->log($e->getMessage(), $user, $e->getCode(), (int) $logNumber->getValue(), $date->format('d-m-Y'), ['userId' => $userId, 'date' => $originalDate ?? '']);

            if (Logger::EVENT_ACCESS_NOT_ALLOWED === $e->getCode()) {
                exit($e->getMessage());
            }
        }

        $dynamicDataDto->setDate($date->format('Y-m-d'));

        $name = 'GPW_'.$date->format('Y-m-d');

        $message = new Swift_Message();
        $message->setFrom($this->getParameter('gpw_email'));

        try {
            $provider = $providerFactory->getProvider(MoneyProvider::PROVIDER_NAME);
            $provider->setUser($user);
            $provider->setDate($date);
            $provider->setDynamicData($dynamicDataDto);
            $provider->execute();

            $message
                   ->attach($provider->getAttachment());
        } catch (Exception $e) {
            $name = $e->getMessage();
            $logger->log(
                Logger::EVENT_ERROR_MESSAGE,
                $user,
                Logger::EVENT_ERROR,
                (int) $logNumber->getValue(),
                $date->format('d-m-Y'),
                [
                    'error_message' => $e->getMessage(),
                    'error_mfile' => $e->getFile(),
                    'error_line' => $e->getLine(),
                    'error_trace' => $e->getTrace(),
                ]
            );
        }

        $message
            ->setTo($userEmailsService->convert($user))
            ->setSubject($name)
            ->setBody($name);

        $result = $mailer->send($message);

        $logger->log(
            Logger::EVENT_SENT_MESSAGE,
            $user,
            Logger::EVENT_SENT,
            (int) $logNumber->getValue(),
            $date->format('d-m-Y'),
            ['sent_result' => $result]
        );

        return $this->render('gpw_cron/index.html.twig');
    }
}
