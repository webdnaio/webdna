<?php

namespace WebDNA\Bundle\AppBundle\EventListener;

use WebDNA\Bundle\AppBundle\Entity\AnalysisProcess;
use WebDNA\Bundle\AppBundle\Event\Analysis\AnalysisWasFinishedEvent;
use WebDNA\Bundle\AppBundle\Event\Analysis\AnalysisWasStartedEvent;
use Symfony\Component\DependencyInjection\ContainerInterface;
use WebDNA\Bundle\AppBundle\Entity\Notification;

class AnalysisListener
{

    /**
     * @var
     */
    protected $container;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    public function onStarted(AnalysisWasStartedEvent $analysisWasStartedEvent)
    {
    }

    /**
     * @param AnalysisWasFinishedEvent $analysisWasFinishedEvent
     * @return boolean|null
     */
    public function onFinished(AnalysisWasFinishedEvent $analysisWasFinishedEvent)
    {
        $analysisProcess = $analysisWasFinishedEvent->getAnalysisProcess();
        $analysisProcess->setStatus(AnalysisProcess::STATUS_COMPLETED);
        $this->container->get('analysis_processes')->save($analysisProcess);

        $website = $analysisProcess->getWebsite();
        $user = $website->getUser();

        if ($analysisProcess->getType() == AnalysisProcess::TYPE_DISCOVER_DEMO) {
            return false;
        }

        $analysisProcessStatsService = $this->container->get('analysis_processes_stats');
        $analysisProcessStatsService->setStatsCounters($analysisProcess);

        $notificationService = $this->container->get('notifications');

        $notification = $notificationService->getStatusByObjectAndType(
            $analysisProcess->getId(),
            Notification::TYPE_ANALYSIS_COMPLETED
        );

        if ($notification == null) {
            $notification = $notificationService->create();
            $notification
                ->setStatus(Notification::STATUS_NOT_SENT)
                ->setUserId($user->getId())
                ->setObjectId($analysisProcess->getId())
                ->setType(Notification::TYPE_ANALYSIS_COMPLETED);
            $notificationService->save($notification);
        }

        return true;
    }
}
