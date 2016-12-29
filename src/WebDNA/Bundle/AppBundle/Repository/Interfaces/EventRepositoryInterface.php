<?php

namespace WebDNA\Bundle\AppBundle\Repository\Interfaces;

use WebDNA\Bundle\AppBundle\Entity\Event;
use WebDNA\Bundle\AppBundle\Entity\AnalysisProcess;
use WebDNA\Bundle\AppBundle\Entity\Website;

/**
 * Interface EventRepositoryInterface
 * @package WebDNA\Bundle\AppBundle\Repository\Interfaces
 */
interface EventRepositoryInterface
{
    /**
     * @param $id
     * @return Event
     */
    public function find($id);

    /**
     * @param Event $event
     * @return mixed
     */
    public function save(Event $event);

    /**
     * @param void
     * @return Int
     */
    public function countAll();

    /**
     * @param Website $website
     * @return Event[]
     */
    public function findByWebsite(Website $website);
}
