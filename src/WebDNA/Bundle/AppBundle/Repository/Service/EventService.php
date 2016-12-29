<?php

namespace WebDNA\Bundle\AppBundle\Repository\Service;

use WebDNA\Bundle\AppBundle\Entity\Event;
use WebDNA\Bundle\AppBundle\Entity\Website;
use WebDNA\Bundle\AppBundle\Repository\Interfaces\EventRepositoryInterface;
use WebDNA\Bundle\AppBundle\Entity\AnalysisProcess;

/**
 * Class EventService
 * @package WebDNA\Bundle\AppBundle\Repository\Service
 */
class EventService
{
    /**
     * @var \WebDNA\Bundle\AppBundle\Repository\Interfaces\EventRepositoryInterface
     */
    protected $eventRepository;

    /**
     * Constructor.
     *
     * @param EventRepositoryInterface $eventRepository
     */
    public function __construct(EventRepositoryInterface $eventRepository)
    {
        $this->eventRepository = $eventRepository;
    }

    /**
     * @param $id
     * @return Event
     */
    public function find($id)
    {
        return $this->eventRepository->find($id);
    }

    /**
     * @param Event $event
     * @return mixed
     */
    public function save(Event $event)
    {
        return $this->eventRepository->save($event);
    }

    /**
     * @param void
     * @return Int
     */
    public function countAll()
    {
        return $this->eventRepository->countAll();
    }

    /**
     * @param Website $website
     * @return Event[]|null
     */
    public function findByWebsite(Website $website)
    {
        return $this->eventRepository->findByWebsite($website);
    }
}
