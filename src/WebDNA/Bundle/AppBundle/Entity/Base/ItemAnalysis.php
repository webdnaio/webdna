<?php

namespace WebDNA\Bundle\AppBundle\Entity\Base;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * Class ItemAnalysis
 * @package WebDNA\Bundle\AppBundle\Entity\Base
 */
class ItemAnalysis
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
     * @ORM\Column(name="object_id", type="bigint")
     */
    protected $objectId;

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
     * @var decimal
     *
     * @ORM\Column(name="class_negative_similarity", type="decimal", precision=6, scale=4, nullable=true)
     */
    protected $class_negative_similarity;

    /**
     * @var decimal
     *
     * @ORM\Column(name="class_positive_similarity", type="decimal", precision=6, scale=4, nullable=true)
     */
    protected $class_positive_similarity;

    /**
     * @var integer
     *
     * @ORM\Column(name="class", type="smallint")
     */
    protected $class;

    /**
     * @var integer
     *
     * @ORM\Column(name="class_system", type="smallint")
     */
    protected $classSystem;

    /**
     * @var integer
     *
     * @ORM\Column(name="class_user", type="smallint")
     */
    protected $classUser;

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
     * @var bool
     *
     * @ORM\Column(name="reviewed", type="boolean")
     */
    protected $reviewed = 0;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\ManyToMany(targetEntity="\WebDNA\Bundle\AppBundle\Entity\ItemMetric", cascade={"persist"}, fetch="EXTRA_LAZY")
     * @ORM\JoinTable(name="item_analysis_item_metric",
     *   joinColumns={
     *     @ORM\JoinColumn(name="item_analysis_id", referencedColumnName="id")
     *   },
     *   inverseJoinColumns={
     *     @ORM\JoinColumn(name="item_metric_id", referencedColumnName="id")
     *   }
     * )
     */
    protected $itemMetrics;


    /**
     * Constructor
     */
    public function __construct()
    {
        $this->itemMetrics = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Set objectId
     *
     * @param integer $objectId
     * @return ItemAnalysis
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

    /**
     * Set type
     *
     * @param integer $type
     * @return ItemAnalysis
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
     * @return ItemAnalysis
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
     * Set class negative similarity.
     *
     * @param $similarity
     * @return Page
     */
    public function setClassNegativeSimilarity($similarity)
    {
        $this->class_negative_similarity = $similarity;

        return $this;
    }

    /**
     * Get class negative similarity.
     *
     * @return decimal
     */
    public function getClassNegativeSimilarity()
    {
        return $this->class_negative_similarity;
    }

    /**
     * Set class positive similarity.
     *
     * @param $similarity
     * @return Page
     */
    public function setClassPositiveSimilarity($similarity)
    {
        $this->class_positive_similarity = $similarity;

        return $this;
    }

    /**
     * Get class positive similarity.
     *
     * @return decimal
     */
    public function getClassPositiveSimilarity()
    {
        return $this->class_positive_similarity;
    }

    /**
     * Set adjusted classification info.
     *
     * @param integer $class
     * @return Page
     */
    public function setClass($class)
    {
        $this->class = $class;

        return $this;
    }

    /**
     * Get adjusted classification info.
     *
     * @return integer
     */
    public function getClass()
    {
        return $this->class;
    }

    /**
     * Set system classification info.
     *
     * @param integer $class
     * @return Page
     */
    public function setClassSystem($class)
    {
        $this->classSystem = $class;

        return $this;
    }

    /**
     * Get system classification info.
     *
     * @return integer
     */
    public function getClassSystem()
    {
        return $this->classSystem;
    }

    /**
     * Set user classification info.
     *
     * @param integer $class
     * @return Page
     */
    public function setClassUser($class)
    {
        $this->classUser = $class;

        return $this;
    }

    /**
     * Get user classification info.
     *
     * @return integer
     */
    public function getClassUser()
    {
        return $this->classUser;
    }

    /**
     * Set score
     *
     * @param string $score
     * @return ItemAnalysis
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
     * @return ItemAnalysis
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
     * @return ItemAnalysis
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
     * Set reviewed
     *
     * @param bool
     * @return ItemAnalysis
     */
    public function setReviewed($reviewed)
    {
        $this->reviewed = $reviewed;

        return $this;
    }

    /**
     * Get reviewed
     *
     * @return bool
     */
    public function getReviewed()
    {
        return $this->reviewed;
    }

    /**
     * Set analysisProcess
     *
     * @param \WebDNA\Bundle\AppBundle\Entity\AnalysisProcess $analysisProcess
     * @return ItemAnalysis
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

    /**
     * Add itemMetric
     *
     * @param \WebDNA\Bundle\AppBundle\Entity\ItemMetric $itemMetric
     * @return Website
     */
    public function addItemMetric(ItemMetric $itemMetric)
    {
        $itemMetric->setAnalysisProcess($this->getAnalysisProcess());
        $itemMetric->setItemAnalysis($this);

        $this->itemMetrics[] = $itemMetric;

        return $this;
    }

    /**
     * Remove itemMetric
     *
     * @param \WebDNA\Bundle\AppBundle\Entity\ItemMetric $itemMetric
     */
    public function removeItemMetric(\WebDNA\Bundle\AppBundle\Entity\ItemMetric $itemMetric)
    {
        $this->itemMetrics->removeElement($itemMetric);
    }

    /**
     * Get itemMetrics
     *
     * @return \WebDNA\Bundle\AppBundle\Entity\ItemMetric[]
     */
    public function getItemMetrics()
    {
        return $this->itemMetrics;
    }
}
