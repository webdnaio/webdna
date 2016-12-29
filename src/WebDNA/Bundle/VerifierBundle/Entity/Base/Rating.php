<?php

namespace WebDNA\Bundle\VerifierBundle\Entity\Base;

use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\ORM\Mapping as ORM;

/**
 * Class Rating
 * @package WebDNA\Bundle\VerifierBundle\Entity\Base
 */
class Rating
{
    /**
     * @var integer
     * @ORM\Id
     * @ORM\Column(name="page_id", type="bigint")
     */
    protected $pageId;

    /**
     * @var integer
     * @ORM\Id
     * @ORM\Column(name="user_id", type="bigint")
     */
    protected $userId;

    /**
     * @var integer
     *
     * @ORM\Column(name="class", type="smallint")
     */
    protected $class;

    /**
     * @var integer
     *
     * @ORM\Column(name="reasons", type="bigint", options={"default" = 0})
     */
    protected $reasons;

    /**
     * @var \DateTime
     * @Gedmo\Timestampable(on="create")
     * @ORM\Column(name="created_at", type="datetime")
     */
    protected $createdAt;

    /**
     * Set pageId
     *
     * @param integer $pageId
     * @return Rating
     */
    public function setPageId($pageId)
    {
        $this->pageId = $pageId;

        return $this;
    }

    /**
     * Get pageId
     *
     * @return integer
     */
    public function getPageId()
    {
        return $this->pageId;
    }

    /**
     * Set userId
     *
     * @param integer $userId
     * @return Rating
     */
    public function setUserId($userId)
    {
        $this->userId = $userId;

        return $this;
    }

    /**
     * Get userId
     *
     * @return integer
     */
    public function getUserId()
    {
        return $this->userId;
    }

    /**
     * Set class
     *
     * @param integer $class
     * @return Rating
     */
    public function setClass($class)
    {
        $this->class = $class;

        return $this;
    }

    /**
     * Get class
     *
     * @return integer
     */
    public function getClass()
    {
        return $this->class;
    }

    /**
     * Set reasons
     *
     * @param integer $reasons
     * @return Rating
     */
    public function setReasons($reasons)
    {
        $this->reasons = $reasons;

        return $this;
    }

    /**
     * Get reasons
     *
     * @return integer
     */
    public function getReasons()
    {
        return $this->reasons;
    }

    /**
     * Set createdAt
     *
     * @param \DateTime $createdAt
     * @return Rating
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
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->pages = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add pages
     *
     * @param \WebDNA\Bundle\VerifierBundle\Entity\Page $pages
     * @return Rating
     */
    public function addPage(\WebDNA\Bundle\VerifierBundle\Entity\Page $pages)
    {
        $this->pages[] = $pages;

        return $this;
    }

    /**
     * Remove pages
     *
     * @param \WebDNA\Bundle\VerifierBundle\Entity\Page $pages
     */
    public function removePage(\WebDNA\Bundle\VerifierBundle\Entity\Page $pages)
    {
        $this->pages->removeElement($pages);
    }

    /**
     * Get pages
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getPages()
    {
        return $this->pages;
    }
}
