<?php

namespace WebDNA\Bundle\AppBundle\Entity\Base;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * Class PageReview
 * @package WebDNA\Bundle\AppBundle\Entity\Base
 */
class PageReview
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
     * @var \WebDNA\Bundle\AppBundle\Entity\Page
     *
     * @ORM\ManyToOne(targetEntity="\WebDNA\Bundle\AppBundle\Entity\Page", cascade={"persist"})
     * @ORM\JoinColumn(name="page_id", referencedColumnName="id", nullable=false)
     */
    protected $page;

    /**
     * @var \WebDNA\Bundle\AppBundle\Entity\AnalysisProcess
     *
     * @ORM\ManyToOne(targetEntity="\WebDNA\Bundle\AppBundle\Entity\AnalysisProcess", cascade={"persist"})
     * @ORM\JoinColumn(name="analysis_process_id", referencedColumnName="id", nullable=false)
     */
    protected $analysisProcess;

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
     * Set page
     *
     * @param \WebDNA\Bundle\AppBundle\Entity\Page $page
     * @return Page
     */
    public function setPage(\WebDNA\Bundle\AppBundle\Entity\Page $page = null)
    {
        $this->page = $page;

        return $this;
    }

    /**
     * Get page
     *
     * @return \WebDNA\Bundle\AppBundle\Entity\Page
     */
    public function getPage()
    {
        return $this->page;
    }


    /**
     * Set AnalysisProcess
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
     * Get AnalysisProcess
     *
     * @return \WebDNA\Bundle\AppBundle\Entity\AnalysisProcess
     */
    public function getAnalysisProcess()
    {
        return $this->analysisProcess;
    }
}
