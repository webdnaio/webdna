<?php

namespace WebDNA\Bundle\AppBundle\Repository\Service;

use Monolog\Logger;
use WebDNA\Bundle\AppBundle\Entity\AnalysisProcess;
use WebDNA\Bundle\AppBundle\Entity\Notification;

/**
 * Class NotificationEmailService
 * @package WebDNA\Bundle\AppBundle\Repository\Service
 */
class NotificationEmailService
{
    /**
     * @var NotificationService
     */
    protected $notificationService;

    /**
     * @var
     */
    protected $mailerService;

    /**
     * @param NotificationService $notificationService
     * @param AnalysisProcessService $analysisProcessService
     * @param AnalysisProcessStatsService $analysisProcessStatsService
     * @param $mailerService
     * @param Logger $loggerService
     * @param \Twig_Environment $templateService
     * @param string $sender_email_address
     * @param string $sender_name
     */
    public function __construct(
        NotificationService $notificationService,
        AnalysisProcessService $analysisProcessService,
        AnalysisProcessStatsService $analysisProcessStatsService,
        \Swift_Mailer $mailerService,
        Logger $loggerService,
        \Twig_Environment $templateService,
        $sender_email_address,
        $sender_name
    ) {
        $this->notificationService = $notificationService;
        $this->mailerService = $mailerService;
        $this->loggerService = $loggerService;
        $this->templateService = $templateService;
        $this->analysisProcessService = $analysisProcessService;
        $this->analysisProcessStats = $analysisProcessStatsService;
        $this->sender_email_address = $sender_email_address;
        $this->sender_name = $sender_name;
    }

    /**
     * @param Notification $notification
     * @return boolean
     */
    public function sendNotification(Notification $notification)
    {
        $analysisProcess = $this->analysisProcessService->find($notification->getObjectId());
        $website = $analysisProcess->getWebsite();
        $user = $website->getUser();

        if ($notification->getStatus() == Notification::STATUS_OK) {
            return true;
        }

        if ($user->getEmailNotificationsEnabled() === true) {
            $subject = 'Backlinks analysis for ' . $website->getName() . ' is ready';
            $message = $this->mailerService->createMessage()
                ->setSubject($subject)
                ->setFrom(
                    [
                        $this->sender_email_address
                        => $this->sender_name
                    ]
                )
                ->setTo($user->getEmail())
                ->setBody(
                    $this->templateService->render(
                        'WebDNAAppBundle:Email:analysis_email_success.html.twig',
                        [
                            'subject' => $subject,
                            'website' => $analysisProcess->getWebsite(),
                            'user' => $user,
                            'analysisProcess' => $analysisProcess,
                            'stats' => $this->analysisProcessStats->getSummary($analysisProcess),
                        ]
                    ),
                    'text/html'
                );

            if ($this->mailerService->send($message)) {
                $status = Notification::STATUS_OK;
            } else {
                $status = Notification::STATUS_FAILED;
            }

            try {
                $notification
                    ->setStatus($status);
                $this->notificationService->save($notification);
            } catch (\Exception $e) {
                $this->loggerService->error(
                    sprintf(
                        'Notification saving exception; analysis process %s: %s',
                        $analysisProcess->getId(),
                        $e->getMessage()
                    )
                );
            }
        }
    }
}
