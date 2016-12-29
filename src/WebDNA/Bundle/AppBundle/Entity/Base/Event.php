<?php

namespace WebDNA\Bundle\AppBundle\Entity\Base;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * Class Event
 * @package WebDNA\Bundle\AppBundle\Entity\Base
 */
class Event
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
     * @ORM\ManyToOne(targetEntity="\WebDNA\Bundle\AppBundle\Entity\Website")
     * @ORM\JoinColumn(name="website_id", referencedColumnName="id")
     */
    protected $website;

    /**
     * @var \WebDNA\Bundle\AppBundle\Entity\AnalysisProcess
     *
     * @ORM\ManyToOne(targetEntity="\WebDNA\Bundle\AppBundle\Entity\AnalysisProcess")
     * @ORM\JoinColumn(name="analysis_process_id", referencedColumnName="id")
     */
    protected $analysisProcess;

    /**
     * @var integer
     *
     * @ORM\Column(name="object_type", type="smallint", nullable=true)
     */
    protected $objectType;

    /**
     * @var integer
     *
     * @ORM\Column(name="object_id", type="bigint", nullable=true)
     */
    protected $objectId;

    /**
     * @var \DateTime
     *
     * @Gedmo\Timestampable(on="create")
     * @ORM\Column(name="created", type="datetime")
     */
    protected $created;

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
     * Set created
     *
     * @param \DateTime $created
     * @return Page
     */
    public function setCreated($created)
    {
        $this->created = $created;

        return $this;
    }

    /**
     * Get created
     *
     * @return \DateTime
     */
    public function getCreated()
    {
        return $this->created;
    }

    /**
     * Set website
     *
     * @param \WebDNA\Bundle\AppBundle\Entity\Website $website
     * @return Page
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
     * Set analysisProcess
     *
     * @param \WebDNA\Bundle\AppBundle\Entity\AnalysisProcess $analysisProcess
     * @return Page
     */
    public function setAnalysisProcess(\WebDNA\Bundle\AppBundle\Entity\AnalysisProcess $analysisProcess = null)
    {
        $this->analysisProcess = $analysisProcess;

        return $this;
    }

    /**
     * Get $analysisProcess
     *
     * @return \WebDNA\Bundle\AppBundle\Entity\AnalysisProcess
     */
    public function getAnalysisProcess()
    {
        return $this->analysisProcess;
    }

    /**
     * Set objectType
     *
     * @param integer $objectType
     * @return Event
     */
    public function setObjectType($objectType)
    {
        $this->objectType = $objectType;

        return $this;
    }

    /**
     * Get objectType
     *
     * @return integer
     */
    public function getObjectType()
    {
        return $this->objectType;
    }

    /**
     * Set objectId
     *
     * @param integer $objectId
     * @return Event
     */
    public function setObjectId($objectId)
    {
        $this->objectId = $objectId;

        return $this;
    }

    /**
     * Get objectId
     *
     * @return integer
     */
    public function getObjectId()
    {
        return $this->objectId;
    }
}
