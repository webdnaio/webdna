<?php

namespace WebDNA\Bundle\AppBundle\EventSubscriber;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use WebDNA\Bundle\AppBundle\Entity\Event;
use WebDNA\Bundle\AppBundle\Event\Analysis\AnalysisEvent;
use WebDNA\Bundle\AppBundle\Event\Analysis\AnalysisWasFinishedEvent;
use WebDNA\Bundle\AppBundle\Event\AnalysisEvents;
use WebDNA\Bundle\AppBundle\Repository\Service\AnalysisProcessService;
use WebDNA\Bundle\AppBundle\Repository\Service\EventService;

/**
 * Class AnalysisSubscriber
 * @package WebDNA\Bundle\AppBundle\EventSubscriber
 */
class AnalysisSubscriber implements EventSubscriberInterface
{
    /**
     * @var
     */
    protected $analysisProcessService;

    /**
     * @var
     */
    protected $eventService;

    /**
     * @param AnalysisProcessService $analysisProcessService
     * @param EventService $eventService
     */
    public function __construct(
        AnalysisProcessService $analysisProcessService,
        EventService $eventService
    ) {
        $this->analysisProcessService = $analysisProcessService;
        $this->eventService = $eventService;
    }

    /**
     * @return array
     */
    public static function getSubscribedEvents()
    {
        return array(
            AnalysisEvents::ANALYSIS_WAS_STARTED => array('onAnalysisWasStarted', 0),
            AnalysisEvents::ANALYSIS_WAS_FINISHED => array('onAnalysisWasFinished', 0),
            AnalysisEvents::ANALYSIS_PAGE_WAS_CREATED => array('onAnalysisPageWasCreated', 0),
            AnalysisEvents::ANALYSIS_PAGE_CLASSIFIED_NEGATIVE => array('onAnalysisPageWasClassified', 0),
            AnalysisEvents::ANALYSIS_PAGE_CLASSIFIED_POSITIVE => array('onAnalysisPageWasClassified', 0),
            AnalysisEvents::ANALYSIS_PAGE_CLASSIFIED_SUSPICIOUS => array('onAnalysisPageWasClassified', 0),
            AnalysisEvents::ANALYSIS_PAGE_CLASSIFIED_UNCLASSIFIED => array('onAnalysisPageWasClassified', 0),
            AnalysisEvents::ANALYSIS_PAGE_CLASSIFIED_UNKNOWN => array('onAnalysisPageWasClassified', 0),
        );
    }

    /**
     * @param AnalysisEvent $event
     */
    public function onAnalysisWasStarted(AnalysisEvent $event)
    {
        $logEvent = $this->prepareLogEvent($event);

        $this->eventService->save($logEvent);
    }

    /**
     * @param AnalysisEvent $event
     */
    public function onAnalysisWasFinished(AnalysisEvent $event)
    {
        $logEvent = $this->prepareLogEvent($event);

        $this->eventService->save($logEvent);
    }

    /**
     * @param AnalysisEvent $event
     */
    public function onAnalysisPageWasCreated(AnalysisEvent $event)
    {
        $logEvent = $this->prepareLogEvent($event);

        $logEvent->setPage($event->getPage());

        $this->eventService->save($logEvent);
    }

    /**
     * @param AnalysisEvent $event
     */
    public function onAnalysisPageWasClassified(AnalysisEvent $event)
    {
        $logEvent = $this->prepareLogEvent($event);

        $logEvent->setPage($event->getPage());

        $this->eventService->save($logEvent);
    }

    protected function prepareLogEvent(AnalysisEvent $event)
    {
        switch (get_class($event)) {
            case 'WebDNA\Bundle\AppBundle\Event\Analysis\AnalysisWasStartedEvent':
                $logEvent = new Event\AnalysisStartedEvent();

                break;

            case 'WebDNA\Bundle\AppBundle\Event\Analysis\AnalysisWasFinishedEvent':
                $logEvent = new Event\AnalysisFinishedEvent();

                break;

            case 'WebDNA\Bundle\AppBundle\Event\Analysis\PageWasCreatedEvent':
                $logEvent = new Event\PageCreatedEvent();

                break;

            case 'WebDNA\Bundle\AppBundle\Event\Analysis\PageWasClassifiedNegativeEvent':
                $logEvent = new Event\PageClassifiedNegativeEvent();

                break;

            case 'WebDNA\Bundle\AppBundle\Event\Analysis\PageWasClassifiedPositiveEvent':
                $logEvent = new Event\PageClassifiedPositiveEvent();

                break;

            case 'WebDNA\Bundle\AppBundle\Event\Analysis\PageWasClassifiedSuspiciousEvent':
                $logEvent = new Event\PageClassifiedSuspiciousEvent();

                break;

            case 'WebDNA\Bundle\AppBundle\Event\Analysis\PageWasClassifiedUnclassifiedEvent':
                $logEvent = new Event\PageClassifiedUnclassifiedEvent();

                break;

            case 'WebDNA\Bundle\AppBundle\Event\Analysis\PageWasClassifiedUnknownEvent':
                $logEvent = new Event\PageClassifiedUnknownEvent();

                break;

            default:
                throw new \Exception('Unsupported event class');
        }

        $logEvent->setAnalysisProcess($event->getAnalysisProcess());
        $logEvent->setWebsite($event->getAnalysisProcess()->getWebsite());

        return $logEvent;
    }
}
