<?php

namespace WebDNA\Bundle\VerifierBundle\Entity\Base;

use Doctrine\ORM\Mapping as ORM;

/**
 * Class Queue
 * @package WebDNA\Bundle\VerifierBundle\Entity\Base
 */
class Queue
{
    /**
     * @var integer
     * @ORM\Id
     * @ORM\Column(name="user_id", type="bigint")
     */
    protected $userId;

    /**
     * @var integer
     * @ORM\Id
     * @ORM\Column(name="page_id", type="bigint")
     */
    protected $pageId;

    /**
     * Set userId
     *
     * @param integer $userId
     * @return Queue
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
     * Set pageId
     *
     * @param integer $pageId
     * @return Queue
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
}
