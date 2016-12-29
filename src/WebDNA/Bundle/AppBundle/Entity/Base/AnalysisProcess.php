<?php

namespace WebDNA\Bundle\AppBundle\Entity\Base;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * Class AnalysisProcess
 * @package WebDNA\Bundle\AppBundle\Entity\Base
 */
class AnalysisProcess
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
     * @ORM\JoinColumn(name="website_id", referencedColumnName="id", nullable=false)
     */
    protected $website;

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
     * @ORM\Column(name="score", type="decimal", precision=10, scale=4, nullable=true)
     */
    protected $score;

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
     * @ORM\Column(name="finished", type="datetime", nullable=true)
     */
    protected $finished;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\ManyToMany(targetEntity="\WebDNA\Bundle\AppBundle\Entity\ItemAnalysis")
     * @ORM\JoinTable(name="analysis_process_item_analysis",
     *   joinColumns={
     *     @ORM\JoinColumn(name="analysis_process_id", referencedColumnName="id")
     *   },
     *   inverseJoinColumns={
     *     @ORM\JoinColumn(name="item_analysis_id", referencedColumnName="id")
     *   }
     * )
     */
    protected $itemAnalyzes;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\ManyToMany(targetEntity="\WebDNA\Bundle\AppBundle\Entity\AnalysisProcessInput")
     * @ORM\JoinTable(name="analysis_process_analysis_process_input",
     *   joinColumns={
     *     @ORM\JoinColumn(name="analysis_process_id", referencedColumnName="id")
     *   },
     *   inverseJoinColumns={
     *     @ORM\JoinColumn(name="analysis_process_input_id", referencedColumnName="id")
     *   }
     * )
     */
    protected $analysisProcessInputs;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\ManyToMany(targetEntity="\WebDNA\Bundle\AppBundle\Entity\AnalysisProcessOutput")
     * @ORM\JoinTable(name="analysis_process_analysis_process_output",
     *   joinColumns={
     *     @ORM\JoinColumn(name="analysis_process_id", referencedColumnName="id")
     *   },
     *   inverseJoinColumns={
     *     @ORM\JoinColumn(name="analysis_process_output_id", referencedColumnName="id")
     *   }
     * )
     */
    protected $analysisProcessOutputs;

    /**
     * @var integer
     *
     * @ORM\Column(name="repeat_analysis", type="integer")
     */
    protected $repeat = 0;

    /**
     * @var \DateTime
     * @ORM\Column(name="repeat_at", type="datetime", nullable=true)
     */
    protected $repeatAt;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->itemAnalyzes = new \Doctrine\Common\Collections\ArrayCollection();
        $this->analysisProcessInputs = new \Doctrine\Common\Collections\ArrayCollection();
        $this->analysisProcessOutputs = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Set type
     *
     * @param integer $type
     * @return AnalysisProcess
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
     * @return AnalysisProcess
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
     * Set score
     *
     * @param string $score
     * @return AnalysisProcess
     */
    public function setScore($score)
    {
        $this->score = $score;

        return $this;
    }

    /**
     * Get score
     *
     * @return string
     */
    public function getScore()
    {
        return $this->score;
    }

    /**
     * Set created
     *
     * @param \DateTime $created
     * @return AnalysisProcess
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
     * Set finished
     *
     * @param \DateTime $finished
     * @return AnalysisProcess
     */
    public function setFinished($finished)
    {
        $this->finished = $finished;

        return $this;
    }

    /**
     * Get finished
     *
     * @return \DateTime
     */
    public function getFinished()
    {
        return $this->finished;
    }

    /**
     * Set website
     *
     * @param \WebDNA\Bundle\AppBundle\Entity\Website $website
     * @return AnalysisProcess
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
     * Add itemAnalyzes
     *
     * @param \WebDNA\Bundle\AppBundle\Entity\ItemAnalysis $itemAnalysis
     * @return AnalysisProcess
     */
    public function addItemAnalysis(\WebDNA\Bundle\AppBundle\Entity\ItemAnalysis $itemAnalysis)
    {
        $itemAnalysis->setAnalysisProcess($this);

        $this->itemAnalyzes[] = $itemAnalysis;

        return $this;
    }

    /**
     * Remove itemAnalyzes
     *
     * @param \WebDNA\Bundle\AppBundle\Entity\ItemAnalysis $itemAnalysis
     */
    public function removeItemAnalysis(\WebDNA\Bundle\AppBundle\Entity\ItemAnalysis $itemAnalysis)
    {
        $this->itemAnalyzes->removeElement($itemAnalysis);
    }

    /**
     * Get itemAnalyzes
     *
     * @return \WebDNA\Bundle\AppBundle\Entity\ItemAnalysis[]
     */
    public function getItemAnalyzes()
    {
        return $this->itemAnalyzes;
    }

    /**
     * Add analysisProcessInputs
     *
     * @param \WebDNA\Bundle\AppBundle\Entity\AnalysisProcessInput $analysisProcessInput
     * @return AnalysisProcess
     */
    public function addAnalysisProcessInput(\WebDNA\Bundle\AppBundle\Entity\AnalysisProcessInput $analysisProcessInput)
    {
        $analysisProcessInput->setAnalysisProcess($this);

        $this->analysisProcessInputs[] = $analysisProcessInput;

        return $this;
    }

    /**
     * Remove analysisProcessInputs
     *
     * @param \WebDNA\Bundle\AppBundle\Entity\AnalysisProcessInput $analysisProcessInput
     */
    public function removeAnalysisProcessInput(
        \WebDNA\Bundle\AppBundle\Entity\AnalysisProcessInput $analysisProcessInput
    ) {
        $this->analysisProcessInputs->removeElement($analysisProcessInput);
    }

    /**
     * Get analysisProcessInputs
     *
     * @return \WebDNA\Bundle\AppBundle\Entity\AnalysisProcessInput[]
     */
    public function getAnalysisProcessInputs()
    {
        return $this->analysisProcessInputs;
    }

    /**
     * Add analysisProcessOutputs
     *
     * @param \WebDNA\Bundle\AppBundle\Entity\AnalysisProcessOutput $analysisProcessOutput
     * @return AnalysisProcess
     */
    public function addAnalysisProcessOutput(
        \WebDNA\Bundle\AppBundle\Entity\AnalysisProcessOutput $analysisProcessOutput
    ) {
        $analysisProcessOutput->setAnalysisProcess($this);

        $this->analysisProcessOutputs[] = $analysisProcessOutput;

        return $this;
    }

    /**
     * Remove analysisProcessOutputs
     *
     * @param \WebDNA\Bundle\AppBundle\Entity\AnalysisProcessOutput $analysisProcessOutput
     */
    public function removeAnalysisProcessOutput(
        \WebDNA\Bundle\AppBundle\Entity\AnalysisProcessOutput $analysisProcessOutput
    ) {
        $this->analysisProcessOutputs->removeElement($analysisProcessOutput);
    }

    /**
     * Get analysisProcessOutputs
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getAnalysisProcessOutputs()
    {
        return $this->analysisProcessOutputs;
    }

    /**
     * Set repeat
     *
     * @param integer $repeat
     * @return AnalysisProcess
     */
    public function setRepeat($repeat)
    {
        $this->repeat = $repeat;

        return $this;
    }

    /**
     * Get repeat
     *
     * @return integer
     */
    public function getRepeat()
    {
        return $this->repeat;
    }

    /**
     * Set repeat
     *
     * @param \DateTime $repeatAt
     * @return AnalysisProcess
     */
    public function setRepeatAt(\DateTime $repeatAt)
    {
        $this->repeatAt = $repeatAt;

        return $this;
    }

    /**
     * Get repeat
     *
     * @return \DateTime
     */
    public function getRepeatAt()
    {
        return $this->repeatAt;
    }
}
