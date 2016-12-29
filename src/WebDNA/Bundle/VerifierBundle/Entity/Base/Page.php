<?php

namespace WebDNA\Bundle\VerifierBundle\Entity\Base;

use Doctrine\ORM\Mapping as ORM;

/**
 * Class Page
 * @package WebDNA\Bundle\VerifierBundle\Entity\Base
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
     * @var integer
     * @ORM\Column(name="website_id", type="bigint", nullable=true)
     */
    protected $websiteId;

    /**
     * @var string
     *
     * @ORM\Column(name="url", type="string", length=255)
     */
    protected $url;

    /**
     * @var string
     *
     * @ORM\Column(name="domain", type="string", length=128)
     */
    protected $domain;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="created_at", type="datetime")
     */
    protected $createdAt;

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
     * Set website id
     *
     * @param integer $website
     * @return Page
     */
    public function setWebsiteId($websiteId)
    {
        $this->websiteId = $websiteId;

        return $this;
    }

    /**
     * Get website id
     *
     * @return integer
     */
    public function getWebsiteId()
    {
        return $this->websiteId;
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
     * Set domain
     *
     * @param string $domain
     * @return Page
     */
    public function setDomain($domain)
    {
        $this->domain = $domain;

        return $this;
    }

    /**
     * Get domain
     *
     * @return string
     */
    public function getDomain()
    {
        return $this->domain;
    }

    /**
     * Set createdAt
     *
     * @param \DateTime $createdAt
     * @return Page
     */
    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * Get createdAt
     *
     * @return \DateTime
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }
}
