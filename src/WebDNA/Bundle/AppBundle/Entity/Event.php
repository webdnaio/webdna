<?php

namespace WebDNA\Bundle\AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Page
 * @ORM\Table(name="event")
 * @ORM\Entity(repositoryClass="\WebDNA\Bundle\AppBundle\Repository\EventRepository")
 * @ORM\InheritanceType("SINGLE_TABLE")
 * @ORM\DiscriminatorColumn(name="type", type="integer")
 * @ORM\DiscriminatorMap({
 *  100 = "\WebDNA\Bundle\AppBundle\Entity\Event\AnalysisStartedEvent",
 *  101 = "\WebDNA\Bundle\AppBundle\Entity\Event\AnalysisFinishedEvent",
 *  200 = "\WebDNA\Bundle\AppBundle\Entity\Event\PageCreatedEvent",
 *  201 = "\WebDNA\Bundle\AppBundle\Entity\Event\PageClassifiedNegativeEvent",
 *  202 = "\WebDNA\Bundle\AppBundle\Entity\Event\PageClassifiedPositiveEvent",
 *  203 = "\WebDNA\Bundle\AppBundle\Entity\Event\PageClassifiedSuspiciousEvent",
 *  204 = "\WebDNA\Bundle\AppBundle\Entity\Event\PageClassifiedUnclassifiedEvent",
 *  205 = "\WebDNA\Bundle\AppBundle\Entity\Event\PageClassifiedUnknownEvent"
 * })
 */
abstract class Event extends Base\Event
{
    const TYPE_PAGE = 1;

    /**
     * @param Page $page
     * @return $this
     */
    public function setPage(Page $page)
    {
        $this->setObjectId($page->getId());
        $this->setObjectType(self::TYPE_PAGE);

        return $this;
    }
}
