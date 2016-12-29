<?php

namespace WebDNA\Bundle\AppBundle\Entity\Base;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * Class Page
 * @package WebDNA\Bundle\AppBundle\Entity\Base
 */
class Page
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
     * @var \WebDNA\Bundle\AppBundle\Entity\ItemAnalysis
     *
     * @ORM\ManyToOne(targetEntity="\WebDNA\Bundle\AppBundle\Entity\ItemAnalysis")
     * @ORM\JoinColumn(name="item_analysis_id", referencedColumnName="id")
     */
    protected $itemAnalysis;

    /**
     * @var integer
     *
     * @ORM\Column(name="type", type="smallint")
     */
    protected $type;

    /**
     * @var integer
     *
     * @ORM\Column(name="status", type="integer")
     */
    protected $status;

    /**
     * @var integer
     *
     * @ORM\Column(name="http_code", type="integer", nullable=true)
     */
    protected $httpCode;

    /**
     * @var string
     *
     * @ORM\Column(name="url", type="string", length=512)
     */
    protected $url;

    /**
     * @var string
     *
     * @ORM\Column(name="effective_url", type="string", length=512, nullable=true)
     */
    protected $effectiveUrl;

    /**
     * @var string
     *
     * @ORM\Column(name="meta_title", type="string", length=512, nullable=true)
     */
    protected $metaTitle;

    /**
     * @var string
     *
     * @ORM\Column(name="meta_description", type="text", nullable=true)
     */
    protected $metaDescription;

    /**
     * @var string
     *
     * @ORM\Column(name="meta_keywords", type="text", nullable=true)
     */
    protected $metaKeywords;

    /**
     * @var integer
     *
     * @ORM\Column(name="meta_charset", type="smallint", nullable=true)
     */
    protected $metaCharset;

    /**
     * @var \DateTime
     *
     * @Gedmo\Timestampable(on="create")
     * @ORM\Column(name="created", type="datetime")
     */
    protected $created;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\ManyToMany(targetEntity="\WebDNA\Bundle\AppBundle\Entity\Link")
     * @ORM\JoinTable(name="page_link",
     *   joinColumns={
     *     @ORM\JoinColumn(name="page_id", referencedColumnName="id")
     *   },
     *   inverseJoinColumns={
     *     @ORM\JoinColumn(name="link_id", referencedColumnName="id")
     *   }
     * )
     */
    protected $links;


    /**
     * Constructor
     */
    public function __construct()
    {
        $this->links = new \Doctrine\Common\Collections\ArrayCollection();
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
     * @return Page
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
     * @return Page
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
     * @param $httpCode
     * @return $this
     */
    public function setHttpCode($httpCode)
    {
        $this->httpCode = $httpCode;

        return $this;
    }

    /**
     * @return int
     */
    public function getHttpCode()
    {
        return $this->httpCode;
    }

    /**
     * Set url
     *
     * @param string $url
     * @return Page
     */
    public function setUrl($url)
    {
        $this->url = $url;

        return $this;
    }

    /**
     * Get url
     *
     * @return string
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * Set effective url
     *
     * @param string $effectiveUrl
     * @return Page
     */
    public function setEffectiveUrl($effectiveUrl)
    {
        $this->effectiveUrl = $effectiveUrl;

        return $this;
    }

    /**
     * Get effective url
     *
     * @return string
     */
    public function getEffectiveUrl()
    {
        return $this->effectiveUrl;
    }

    /**
     * Set metaTitle
     *
     * @param string $metaTitle
     * @return Page
     */
    public function setMetaTitle($metaTitle)
    {
        $this->metaTitle = $metaTitle;

        return $this;
    }

    /**
     * Get metaTitle
     *
     * @return string
     */
    public function getMetaTitle()
    {
        return $this->metaTitle;
    }

    /**
     * Set metaDescription
     *
     * @param string $metaDescription
     * @return Page
     */
    public function setMetaDescription($metaDescription)
    {
        $this->metaDescription = $metaDescription;

        return $this;
    }

    /**
     * Get metaDescription
     *
     * @return string
     */
    public function getMetaDescription()
    {
        return $this->metaDescription;
    }

    /**
     * Set metaKeywords
     *
     * @param string $metaKeywords
     * @return Page
     */
    public function setMetaKeywords($metaKeywords)
    {
        $this->metaKeywords = $metaKeywords;

        return $this;
    }

    /**
     * Get metaKeywords
     *
     * @return string
     */
    public function getMetaKeywords()
    {
        return $this->metaKeywords;
    }

    /**
     * Set metaCharset
     *
     * @param integer $metaCharset
     * @return Page
     */
    public function setMetaCharset($metaCharset)
    {
        $this->metaCharset = $metaCharset;

        return $this;
    }

    /**
     * Get metaCharset
     *
     * @return integer
     */
    public function getMetaCharset()
    {
        return $this->metaCharset;
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
     * Set itemAnalysis
     *
     * @param \WebDNA\Bundle\AppBundle\Entity\ItemAnalysis $itemAnalysis
     * @return Page
     */
    public function setItemAnalysis(\WebDNA\Bundle\AppBundle\Entity\ItemAnalysis $itemAnalysis = null)
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

    /**
     * Add link
     *
     * @param \WebDNA\Bundle\AppBundle\Entity\Link $link
     * @return Page
     */
    public function addLink(\WebDNA\Bundle\AppBundle\Entity\Link $link)
    {
        $link->setSourcePage($this);

        $this->links[] = $link;

        return $this;
    }

    /**
     * Remove link
     *
     * @param \WebDNA\Bundle\AppBundle\Entity\Link $link
     */
    public function removeLink(\WebDNA\Bundle\AppBundle\Entity\Link $link)
    {
        $this->links->removeElement($link);
    }

    /**
     * Get links
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getLinks()
    {
        return $this->links;
    }
}
