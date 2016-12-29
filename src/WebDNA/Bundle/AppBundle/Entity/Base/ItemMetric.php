<?php

namespace WebDNA\Bundle\AppBundle\Entity\Base;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * Class ItemMetric
 * @package WebDNA\Bundle\AppBundle\Entity\Base
 */
class ItemMetric
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
     * @var \WebDNA\Bundle\AppBundle\Entity\ItemAnalysis
     *
     * @ORM\ManyToOne(targetEntity="\WebDNA\Bundle\AppBundle\Entity\ItemAnalysis")
     * @ORM\JoinColumn(name="item_analysis_id", referencedColumnName="id", nullable=false)
     */
    protected $itemAnalysis;

    /**
     * @var integer
     *
     * @ORM\Column(name="type", type="smallint")
     */
    protected $type;

    /**
     * @var string
     *
     * @ORM\Column(name="value_numeric_1", type="decimal", precision=16, scale=4, nullable=true)
     */
    protected $value_numeric_1;

    /**
     * @var string
     *
     * @ORM\Column(name="value_numeric_2", type="decimal", precision=16, scale=4, nullable=true)
     */
    protected $value_numeric_2;

    /**
     * @var string
     *
     * @ORM\Column(name="value_numeric_3", type="decimal", precision=16, scale=4, nullable=true)
     */
    protected $value_numeric_3;

    /**
     * @var string
     *
     * @ORM\Column(name="value_numeric_4", type="decimal", precision=16, scale=4, nullable=true)
     */
    protected $value_numeric_4;

    /**
     * @var string
     *
     * @ORM\Column(name="value_numeric_5", type="decimal", precision=16, scale=4, nullable=true)
     */
    protected $value_numeric_5;

    /**
     * @var string
     *
     * @ORM\Column(name="value_text_1", type="text",nullable=true)
     */
    protected $value_text_1;

    /**
     * @var string
     *
     * @ORM\Column(name="value_text_2", type="text",nullable=true)
     */
    protected $value_text_2;

    /**
     * @var string
     *
     * @ORM\Column(name="value_text_3", type="text",nullable=true)
     */
    protected $value_text_3;

    /**
     * @var string
     *
     * @ORM\Column(name="value_text_4", type="text",nullable=true)
     */
    protected $value_text_4;

    /**
     * @var string
     *
     * @ORM\Column(name="value_text_5", type="text",nullable=true)
     */
    protected $value_text_5;

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
     * Set type
     *
     * @param integer $type
     * @return ItemMetric
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
     * Set numeric value 1
     *
     * @param string $value
     * @return ItemMetric
     */
    public function setValueNumeric1($value)
    {
        $this->value_numeric_1 = $value;

        return $this;
    }

    /**
     * Get numeric value 1
     *
     * @return string
     */
    public function getValueNumeric1()
    {
        return $this->value_numeric_1;
    }

    /**
     * Set numeric value 2
     *
     * @param string $value
     * @return ItemMetric
     */
    public function setValueNumeric2($value)
    {
        $this->value_numeric_2 = $value;

        return $this;
    }

    /**
     * Get numeric value 2
     *
     * @return string
     */
    public function getValueNumeric2()
    {
        return $this->value_numeric_2;
    }


    /**
     * Set numeric value 3
     *
     * @param string $value
     * @return ItemMetric
     */
    public function setValueNumeric3($value)
    {
        $this->value_numeric_3 = $value;

        return $this;
    }

    /**
     * Get numeric value 3
     *
     * @return string
     */
    public function getValueNumeric3()
    {
        return $this->value_numeric_3;
    }


    /**
     * Set numeric value 4
     *
     * @param string $value
     * @return ItemMetric
     */
    public function setValueNumeric4($value)
    {
        $this->value_numeric_4 = $value;

        return $this;
    }

    /**
     * Get numeric value 4
     *
     * @return string
     */
    public function getValueNumeric4()
    {
        return $this->value_numeric_4;
    }


    /**
     * Set numeric value 5
     *
     * @param string $value
     * @return ItemMetric
     */
    public function setValueNumeric5($value)
    {
        $this->value_numeric_5 = $value;

        return $this;
    }

    /**
     * Get numeric value 5
     *
     * @return string
     */
    public function getValueNumeric5()
    {
        return $this->value_numeric_5;
    }

    /**
     * Set text value 1
     *
     * @param string $value
     * @return ItemMetric
     */
    public function setValueText1($value)
    {
        $this->value_text_1 = $value;

        return $this;
    }

    /**
     * Get text value 1
     *
     * @return string
     */
    public function getValueText1()
    {
        return $this->value_text_1;
    }

    /**
     * Set text value 2
     *
     * @param string $value
     * @return ItemMetric
     */
    public function setValueText2($value)
    {
        $this->value_text_2 = $value;

        return $this;
    }

    /**
     * Get text value 2
     *
     * @return string
     */
    public function getValueText2()
    {
        return $this->value_text_2;
    }

    /**
     * Set text value 3
     *
     * @param string $value
     * @return ItemMetric
     */
    public function setValueText3($value)
    {
        $this->value_text_3 = $value;

        return $this;
    }

    /**
     * Get text value 3
     *
     * @return string
     */
    public function getValueText3()
    {
        return $this->value_text_4;
    }

    /**
     * Set text value 4
     *
     * @param string $value
     * @return ItemMetric
     */
    public function setValueText4($value)
    {
        $this->value_text_4 = $value;

        return $this;
    }

    /**
     * Get text value 4
     *
     * @return string
     */
    public function getValueText4()
    {
        return $this->value_text_4;
    }

    /**
     * Set text value 5
     *
     * @param string $value
     * @return ItemMetric
     */
    public function setValueText5($value)
    {
        $this->value_text_5 = $value;

        return $this;
    }

    /**
     * Get text value 5
     *
     * @return string
     */
    public function getValueText5()
    {
        return $this->value_text_5;
    }

    /**
     * Set created
     *
     * @param \DateTime $created
     * @return ItemMetric
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
     * Set itemAnalysis
     *
     * @param \WebDNA\Bundle\AppBundle\Entity\ItemAnalysis $itemAnalysis
     * @return ItemMetric
     */
    public function setItemAnalysis(\WebDNA\Bundle\AppBundle\Entity\ItemAnalysis $itemAnalysis)
    {
        $this->itemAnalysis = $itemAnalysis;

        return $this;
    }

    /**
     * Get itemAnalysis
     *
     * @return \WebDNA\Bundle\AppBundle\Entity\ItemAnalysis
     */
    public function getItemAnalysis()
    {
        return $this->itemAnalysis;
    }
}
