<?php

namespace App\Controller;

use App\Entity\Settings;
use App\Entity\User;
use App\Helper\AccessHelper;
use App\Helper\SettingsHelper;
use App\Helper\UserHelper;
use App\Helper\Users\UsersFactory;
use App\Service\Logger\Logger;
use App\Service\Providers\GpwProvider;
use App\Service\Providers\ProviderFactory;
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
        AccessHelper $accessHelper,
        UserHelper $userHelper,
        Logger $logger,
        SettingsHelper $settingsHelper,
        ProviderFactory $providerFactory,
        UsersFactory $usersFactory
    ) {
        if (!$userId) {
            $userId = User::USER_CRON;
        }
        /** @var User $usingUser */
        $usingUser = $this->getDoctrine()->getRepository(User::class)->find((int) $userId);
        /** @var Settings $logNumber */
        $logNumber = $this->getDoctrine()->getRepository(Settings::class)->findOneBy(['name' => 'log_number']);
        $settingsHelper->updateLogNumber($logNumber);

        $originalDate = $date;
        if (!$date = DateTime::createFromFormat('d-m-Y', $date)) {
            $date = new DateTime();
        }

        $logger->log(Logger::EVENT_START_MESSAGE, $usingUser, Logger::EVENT_START, (int) $logNumber->getValue(), $date->format('d-m-Y'), ['userId' => $userId, 'date' => $originalDate ?? '']);

        try {
            $accessHelper->isAccess($date->format('d-m-Y'), $allowed);
        } catch (Exception $e) {
            $logger->log($e->getMessage(), $usingUser, $e->getCode(), (int) $logNumber->getValue(), $date->format('d-m-Y'), ['userId' => $userId, 'date' => $originalDate ?? '']);

            if (Logger::EVENT_ACCESS_NOT_ALLOWED === $e->getCode()) {
                exit($e->getMessage());
            }
        }

        $specialData = [
            'date' => $date->format('Y-m-d'),
        ];

        $userId = 1;

        $name = 'GPW_'.$date->format('Y-m-d');

        $message = new Swift_Message();
        $message->setFrom($this->getParameter('gpw_email'));

        try {
            $user = $usersFactory->factory($userId);

            $provider = $providerFactory->getProvider(GpwProvider::PROVIDER_NAME);
            $provider->setUser($user);
            $provider->setDate($date);
            $provider->setSpecialData($specialData);
            $provider->execute();

            $message
                   ->attach($provider->getAttachment());
        } catch (Exception $e) {
            $name = $e->getMessage();
        }

        $message
            ->setTo($userHelper->getUserMails($userId))
            ->setSubject($name)
            ->setBody($name);

        $mailer->send($message);

        return $this->render('gpw_cron/index.html.twig');
    }
}
