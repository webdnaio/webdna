<?php

namespace WebDNA\Bundle\AppBundle\Entity\Base;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * Class AnalysisProcessStats
 * @package WebDNA\Bundle\AppBundle\Entity\Base
 */
class AnalysisProcessStats
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
     * @ORM\ManyToOne(targetEntity="\WebDNA\Bundle\AppBundle\Entity\AnalysisProcess", cascade={"persist"})
     * @ORM\JoinColumn(name="analysis_process_id", referencedColumnName="id", nullable=false, unique=true)
     */
    protected $analysisProcess;

    /**
     * @var integer
     *
     * @ORM\Column(name="negative", type="integer")
     */
    protected $negative = 0;

    /**
     * @var integer
     *
     * @ORM\Column(name="positive", type="integer")
     */
    protected $positive = 0;

    /**
     * @var integer
     *
     * @ORM\Column(name="suspicious", type="integer")
     */
    protected $suspicious = 0;

    /**
     * @var integer
     *
     * @ORM\Column(name="malware", type="integer")
     */
    protected $malware = 0;

    /**
     * @var integer
     *
     * @ORM\Column(name="analyzed_domains", type="integer")
     */
    protected $analyzedDomains = 0;

    /**
     * Total backlinks found
     * @var integer
     *
     * @ORM\Column(name="links_found", type="integer")
     */
    protected $linksFound = 0;

    /**
     * @var integer
     *
     * @ORM\Column(name="links_follow", type="integer")
     */
    protected $linksFollow = 0;

    /**
     * @var integer
     *
     * @ORM\Column(name="links_nofollow", type="integer")
     */
    protected $linksNofollow = 0;

    /**
     * @var integer
     *
     * @ORM\Column(name="total_pages_analyzed", type="integer")
     */
    protected $totalPagesAnalyzed = 0;

    /**
     * New urls found since previous analysis
     * @var integer
     *
     * @ORM\Column(name="new_urls_found", type="integer")
     */
    protected $newUrlsFound = 0;

    /**
     * @var integer
     *
     * @ORM\Column(name="user_classified_urls", type="integer")
     */
    protected $userClassifiedUrls = 0;

    /**
     * @var integer
     *
     * @ORM\Column(name="user_unclassified_urls", type="integer")
     */
    protected $userUnclassifiedUrls = 0;

    /**
     * @var integer
     *
     * @ORM\Column(name="reviewed", type="integer")
     */
    protected $reviewed = 0;

    /**
     * @var \DateTime
     *
     * @Gedmo\Timestampable(on="update")
     * @ORM\Column(name="last_update", type="datetime")
     */
    protected $lastUpdate;

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
     * Set positive counter
     *
     * @param integer $positive
     * @return AnalysisProcessStats
     */
    public function setPositive($positive)
    {
        $this->positive = $positive;

        return $this;
    }

    /**
     * Get positive counter
     *
     * @return integer
     */
    public function getPositive()
    {
        return $this->positive;
    }

    /**
     * Set suspicious counter
     *
     * @param integer $suspicious
     * @return AnalysisProcessStats
     */
    public function setSuspicious($suspicious)
    {
        $this->suspicious = $suspicious;

        return $this;
    }

    /**
     * Get suspicious counter
     *
     * @return integer
     */
    public function getSuspicious()
    {
        return $this->suspicious;
    }

    /**
     * Set negative counter
     *
     * @param integer $negative
     * @return AnalysisProcessStats
     */
    public function setNegative($negative)
    {
        $this->negative = $negative;

        return $this;
    }

    /**
     * Get negative counter
     *
     * @return integer
     */
    public function getNegative()
    {
        return $this->negative;
    }

    /**
     * Set malware counter
     *
     * @param integer $malware
     * @return AnalysisProcessStats
     */
    public function setMalware($malware)
    {
        $this->malware = $malware;

        return $this;
    }

    /**
     * Get malware counter
     *
     * @return integer
     */
    public function getMalware()
    {
        return $this->malware;
    }

    /**
     * Get analyzedDomains counter
     *
     * @return integer
     */
    public function getAnalyzedDomains()
    {
        return $this->analyzedDomains;
    }

    /**
     * Set malware counter
     *
     * @param integer $analyzedDomains
     * @return AnalysisProcessStats
     */
    public function setAnalyzedDomains($analyzedDomains)
    {
        $this->analyzedDomains = $analyzedDomains;

        return $this;
    }

    /**
     * Get linksFound counter
     *
     * @return integer
     */
    public function getLinksFound()
    {
        return $this->linksFound;
    }

    /**
     * Set linksFound counter
     *
     * @param integer $linksFound
     * @return AnalysisProcessStats
     */
    public function setLinksFound($linksFound)
    {
        $this->linksFound = $linksFound;

        return $this;
    }

    /**
     * Set last update datetime
     *
     * @param \DateTime $lastUpdate
     * @return AnalysisProcess
     */
    public function setLastUpdate($lastUpdate)
    {
        $this->lastUpdate = $lastUpdate;

        return $this;
    }

    /**
     * Get last update datetime
     *
     * @return \DateTime
     */
    public function getLastUpdate()
    {
        return $this->lastUpdate;
    }

    /**
     * Set analysis process
     *
     * @param \WebDNA\Bundle\AppBundle\Entity\AnalysisProcess $analysisProcess
     * @return AnalysisProcessStats
     */
    public function setAnalysisProcess(\WebDNA\Bundle\AppBundle\Entity\AnalysisProcess $analysisProcess = null)
    {
        $this->analysisProcess = $analysisProcess;

        return $this;
    }

    /**
     * Get analysis process
     *
     * @return \WebDNA\Bundle\AppBundle\Entity\AnalysisProcess
     */
    public function getAnalysisProcess()
    {
        return $this->analysisProcess;
    }

    /**
     * @return int
     */
    public function getLinksFollow()
    {
        return $this->linksFollow;
    }

    /**
     * @param int $linksFollow
     */
    public function setLinksFollow($linksFollow)
    {
        $this->linksFollow = $linksFollow;
    }

    /**
     * @return int
     */
    public function getLinksNofollow()
    {
        return $this->linksNofollow;
    }

    /**
     * @param int $linksNofollow
     */
    public function setLinksNofollow($linksNofollow)
    {
        $this->linksNofollow = $linksNofollow;
    }

    /**
     * @return int
     */
    public function getTotalPagesAnalyzed()
    {
        return $this->totalPagesAnalyzed;
    }

    /**
     * @param int $totalPagesAnalyzed
     */
    public function setTotalPagesAnalyzed($totalPagesAnalyzed)
    {
        $this->totalPagesAnalyzed = $totalPagesAnalyzed;
    }

    /**
     * @return int
     */
    public function getNewUrlsFound()
    {
        return $this->newUrlsFound;
    }

    /**
     * @param int $newUrlsFound
     */
    public function setNewUrlsFound($newUrlsFound)
    {
        $this->newUrlsFound = $newUrlsFound;
    }

    /**
     * @return int
     */
    public function getUserClassifiedUrls()
    {
        return $this->userClassifiedUrls;
    }

    /**
     * @param int $userClassifiedUrls
     */
    public function setUserClassifiedUrls($userClassifiedUrls)
    {
        $this->userClassifiedUrls = $userClassifiedUrls;
    }

    /**
     * @return int
     */
    public function getUserUnclassifiedUrls()
    {
        return $this->userUnclassifiedUrls;
    }

    /**
     * @param int $userUnclassifiedUrls
     */
    public function setUserUnclassifiedUrls($userUnclassifiedUrls)
    {
        $this->userUnclassifiedUrls = $userUnclassifiedUrls;
    }

    /**
     * @return int
     */
    public function getReviewed()
    {
        return $this->reviewed;
    }

    /**
     * @param int $reviewed
     */
    public function setReviewed($reviewed)
    {
        $this->reviewed = $reviewed;
    }
}
