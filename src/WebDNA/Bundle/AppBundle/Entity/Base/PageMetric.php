<?php

namespace WebDNA\Bundle\AppBundle\Entity\Base;

use Doctrine\ORM\Mapping as ORM;

class PageMetric
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
     * Summary of nofollow and follow links
     *
     * @var integer
     *
     * @ORM\Column(name="pointing_links_count", type="integer")
     */
    protected $pointingLinksCount;

    /**
     * @var integer
     *
     * @ORM\Column(name="pointing_links_follow_count", type="integer")
     */
    protected $pointingLinksFollowCount;

    /**
     * @var integer
     *
     * @ORM\Column(name="total_links_on_site_count", type="integer")
     */
    protected $totalLinksOnSiteCount;

    /**
     * @var integer
     *
     * @ORM\Column(name="total_links_on_site_follow_count", type="integer")
     */
    protected $totalLinksOnSiteFollowCount;

    /**
     * @var integer
     *
     * @ORM\Column(name="total_links_on_site_nofollow_count", type="integer")
     */
    protected $totalLinksOnSiteNofollowCount;

    /**
     * @var string
     *
     * @ORM\Column(name="anchor", type="string", nullable=true)
     */
    protected $anchor;

    /**
     * @var \WebDNA\Bundle\AppBundle\Entity\ItemAnalysis
     *
     * @ORM\ManyToOne(targetEntity="\WebDNA\Bundle\AppBundle\Entity\ItemAnalysis")
     * @ORM\JoinColumn(name="item_analysis_id", referencedColumnName="id", nullable=true)
     */
    protected $itemAnalysis;

    /**
     * @var integer
     *
     * @ORM\Column(name="domain_authority", type="integer")
     */
    protected $domainAuthority = 0;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="domain_age", type="datetime", nullable=true)
     */
    protected $domainAge;

    /**
     * @var \WebDNA\Bundle\AppBundle\Entity\Website
     *
     * @ORM\ManyToOne(targetEntity="\WebDNA\Bundle\AppBundle\Entity\Website")
     * @ORM\JoinColumn(name="website_id", referencedColumnName="id", nullable=false)
     */
    protected $website;

    /**
     * @var \WebDNA\Bundle\AppBundle\Entity\Page
     *
     * @ORM\ManyToOne(targetEntity="\WebDNA\Bundle\AppBundle\Entity\Page")
     * @ORM\JoinColumn(name="page_id", referencedColumnName="id", nullable=false)
     */
    protected $page;

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
     * @return int
     */
    public function getPointingLinksCount()
    {
        return $this->pointingLinksCount;
    }

    /**
     * @param int $pointingLinksCount
     */
    public function setPointingLinksCount($pointingLinksCount)
    {
        $this->pointingLinksCount = $pointingLinksCount;
    }

    /**
     * @return int
     */
    public function getPointingLinksFollowCount()
    {
        return $this->pointingLinksFollowCount;
    }

    /**
     * @param int $pointingLinksFollowCount
     */
    public function setPointingLinksFollowCount($pointingLinksFollowCount)
    {
        $this->pointingLinksFollowCount = $pointingLinksFollowCount;
    }

    /**
     * @return int
     */
    public function getTotalLinksOnSiteCount()
    {
        return $this->totalLinksOnSiteCount;
    }

    /**
     * @param int $totalLinksOnSiteCount
     */
    public function setTotalLinksOnSiteCount($totalLinksOnSiteCount)
    {
        $this->totalLinksOnSiteCount = $totalLinksOnSiteCount;
    }

    /**
     * @return int
     */
    public function getTotalLinksOnSiteFollowCount()
    {
        return $this->totalLinksOnSiteFollowCount;
    }

    /**
     * @param int $totalLinksOnSiteFollowCount
     */
    public function setTotalLinksOnSiteFollowCount($totalLinksOnSiteFollowCount)
    {
        $this->totalLinksOnSiteFollowCount = $totalLinksOnSiteFollowCount;
    }

    /**
     * @return int
     */
    public function getTotalLinksOnSiteNofollowCount()
    {
        return $this->totalLinksOnSiteNofollowCount;
    }

    /**
     * @param int $totalLinksOnSiteNofollowCount
     */
    public function setTotalLinksOnSiteNofollowCount($totalLinksOnSiteNofollowCount)
    {
        $this->totalLinksOnSiteNofollowCount = $totalLinksOnSiteNofollowCount;
    }

    /**
     * @return string
     */
    public function getAnchor()
    {
        return $this->anchor;
    }

    /**
     * @param string $anchor
     */
    public function setAnchor($anchor)
    {
        $this->anchor = $anchor;
    }

    /**
     * @return int
     */
    public function getDomainAuthority()
    {
        return $this->domainAuthority;
    }

    /**
     * @param int $domainAuthority
     */
    public function setDomainAuthority($domainAuthority)
    {
        $this->domainAuthority = $domainAuthority;
    }

    /**
     * @return \DateTime
     */
    public function getDomainAge()
    {
        return $this->domainAge;
    }

    /**
     * @param \DateTime $domainAge
     */
    public function setDomainAge($domainAge)
    {
        $this->domainAge = $domainAge;
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
     * @return \WebDNA\Bundle\AppBundle\Entity\Page
     */
    public function getPage()
    {
        return $this->page;
    }

    /**
     * @param \WebDNA\Bundle\AppBundle\Entity\Page $page
     */
    public function setPage(\WebDNA\Bundle\AppBundle\Entity\Page $page = null)
    {
        $this->page = $page;
    }

    /**
     * @return \WebDNA\Bundle\AppBundle\Entity\ItemAnalysis
     */
    public function getItemAnalysis()
    {
        return $this->itemAnalysis;
    }

    /**
     * @param \WebDNA\Bundle\AppBundle\Entity\ItemAnalysis $itemAnalysis
     */
    public function setItemAnalysis(\WebDNA\Bundle\AppBundle\Entity\ItemAnalysis $itemAnalysis)
    {
        $this->itemAnalysis = $itemAnalysis;
    }
}
