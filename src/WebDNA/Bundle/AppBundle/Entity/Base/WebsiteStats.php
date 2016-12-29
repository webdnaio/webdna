<?php

namespace WebDNA\Bundle\AppBundle\Entity\Base;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * Class WebsiteStats
 * @package WebDNA\Bundle\AppBundle\Entity\Base
 */
class WebsiteStats
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="bigint")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="CUSTOM")
     * @ORM\CustomIdGenerator(class="\WebDNA\Bundle\CommonBundle\Doctrine\ORM\Id\BigIntGenerator")
     */
    protected $id;

    /**
     * @var \WebDNA\Bundle\AppBundle\Entity\Website
     *
     * @ORM\ManyToOne(targetEntity="\WebDNA\Bundle\AppBundle\Entity\Website", cascade={"persist"})
     * @ORM\JoinColumn(name="analysis_process_id", referencedColumnName="id", nullable=false, unique=true)
     */
    protected $website;

    /**
     * @var integer
     *
     * @ORM\Column(name="analyzes_count", type="integer")
     */
    protected $analyzesCount = 0;

    /**
     * @var integer
     *
     * @ORM\Column(name="last_analysis_process_id", type="bigint")
     */
    protected $lastAnalysisProcessId;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="last_analysis_created", type="datetime")
     */
    protected $lastAnalysisProcessCreated;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="last_analysis_finished", type="datetime")
     */
    protected $lastAnalysisProcessFinished;


    /**
     * Constructor
     */
    public function __construct()
    {
    }

    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set website
     *
     * @param \WebDNA\Bundle\AppBundle\Entity\Website $website
     * @return Website
     */
    public function setWebsite(\WebDNA\Bundle\AppBundle\Entity\Website $website = null)
    {
        $this->website = $website;

        return $this;
    }

    /**
     * Get website
     *
     * @return \WebDNA\Bundle\AppBundle\Entity\Website
     */
    public function getWebsite()
    {
        return $this->website;
    }

    /**
     * @param \DateTime $lastUpdate
     * @return Website
     */
    public function setLastAnalysisCreated($lastUpdate)
    {
        $this->lastAnalysisProcessCreated = $lastUpdate;

        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getLastAnalysisCreated()
    {
        return $this->lastAnalysisProcessCreated;
    }

    /**
     * @param \DateTime $lastUpdate
     * @return Website
     */
    public function setLastAnalysisFinished($lastUpdate)
    {
        $this->lastAnalysisProcessCreated = $lastUpdate;

        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getLastAnalysisFinished()
    {
        return $this->lastAnalysisProcessFinished;
    }

    /**
     * Set counter of analyzes assigned to website
     *
     * @param $count
     * @return Website
     */
    public function setAnalyzesCount($count)
    {
        $this->analyzesCount = $count;

        return $this;
    }

    /**
     * @return integer
     */
    public function getAnalyzesCount()
    {
        return $this->analyzesCount;
    }

    /**
     * @param integer $id
     * @return Website
     */
    public function setLastAnalysisProcessId($id)
    {
        $this->lastAnalysisProcessId = $id;

        return $this;
    }

    /**
     * @return integer
     */
    public function getLastAnalysisProcessId()
    {
        return $this->lastAnalysisProcessId;
    }
}
