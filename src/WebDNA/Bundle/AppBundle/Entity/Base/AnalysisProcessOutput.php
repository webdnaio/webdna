<?php

namespace WebDNA\Bundle\AppBundle\Entity\Base;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * Class AnalysisProcessOutput
 * @package WebDNA\Bundle\AppBundle\Entity\Base
 */
class AnalysisProcessOutput
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
     * @var \WebDNA\Bundle\AppBundle\Entity\AnalysisProcess
     *
     * @ORM\ManyToOne(targetEntity="\WebDNA\Bundle\AppBundle\Entity\AnalysisProcess")
     * @ORM\JoinColumn(name="analysis_process_id", referencedColumnName="id", nullable=false)
     */
    protected $analysisProcess;

    /**
     * @var integer
     *
     * @ORM\Column(name="type", type="smallint")
     */
    protected $type;

    /**
     * @var integer
     *
     * @ORM\Column(name="status", type="smallint")
     */
    protected $status;

    /**
     * @var string
     *
     * @ORM\Column(name="path", type="string", length=255)
     */
    protected $path;

    /**
     * @var \DateTime
     *
     * @Gedmo\Timestampable(on="create")
     * @ORM\Column(name="created", type="datetime")
     */
    protected $created;

    /**
     * @var \DateTime
     *
     * @Gedmo\Timestampable(on="update")
     * @ORM\Column(name="updated", type="datetime", nullable=true)
     */
    protected $updated;


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
     * Set type
     *
     * @param integer $type
     * @return AnalysisProcessOutput
     */
    public function setType($type)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * Get type
     *
     * @return integer
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Set status
     *
     * @param integer $status
     * @return AnalysisProcessOutput
     */
    public function setStatus($status)
    {
        $this->status = $status;

        return $this;
    }

    /**
     * Get status
     *
     * @return integer
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * Set path
     *
     * @param string $path
     * @return AnalysisProcessOutput
     */
    public function setPath($path)
    {
        $this->path = $path;

        return $this;
    }

    /**
     * Get path
     *
     * @return string
     */
    public function getPath()
    {
        return $this->path;
    }

    /**
     * Set created
     *
     * @param \DateTime $created
     * @return AnalysisProcessOutput
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
     * Set updated
     *
     * @param \DateTime $updated
     * @return AnalysisProcessOutput
     */
    public function setUpdated($updated)
    {
        $this->updated = $updated;

        return $this;
    }

    /**
     * Get updated
     *
     * @return \DateTime
     */
    public function getUpdated()
    {
        return $this->updated;
    }

    /**
     * Set analysisProcess
     *
     * @param \WebDNA\Bundle\AppBundle\Entity\AnalysisProcess $analysisProcess
     * @return AnalysisProcessOutput
     */
    public function setAnalysisProcess(\WebDNA\Bundle\AppBundle\Entity\AnalysisProcess $analysisProcess)
    {
        $this->analysisProcess = $analysisProcess;

        return $this;
    }

    /**
     * Get analysisProcess
     *
     * @return \WebDNA\Bundle\AppBundle\Entity\AnalysisProcess
     */
    public function getAnalysisProcess()
    {
        return $this->analysisProcess;
    }
}
