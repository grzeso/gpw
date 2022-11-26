<?php

namespace App\Controller;

use App\Entity\User\User;
use App\Entity\User\UsersEmails;
use App\Service\Access\AccessService;
use App\Service\Dto\DynamicDataDto;
use App\Service\Logger\Logger;
use App\Service\Providers\ProviderFactory;
use App\Service\Settings\SettingsService;
use DateTime;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Routing\Annotation\Route;

class GpwCronController extends AbstractController
{
    #[Route(path: '/cron/gpw/{id}/{originalDate?}/{allowed?}', name: 'gpw_cron', methods: ['GET'])]
    public function execute(
        int $id,
        ?string $originalDate,
        ?int $allowed,
        User $user,
        MailerInterface $mailer,
        Logger $logger,
        ProviderFactory $providerFactory,
        DynamicDataDto $dynamicDataDto,
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

        $message = new Email();

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

            $message->attach($provider->getBody(), $provider->getAttachmentName(), $provider->getType());
        } catch (Exception $e) {
            $name = $e->getMessage();
            $logger->logError($e);
        }

        $message
            ->from($this->getParameter('gpw.email'))
            ->to(...$user->getUsersEmails()->map(fn (UsersEmails $emails) => $emails->getEmail())->toArray())
            ->subject($name)
            ->html($name);

        try {
            $mailer->send($message);
            $logger->logSent(1);
        } catch (TransportExceptionInterface $e) {
            $logger->logSent(0);
        }

        return $this->render('gpw_cron/index.html.twig');
    }
}
