<?php

namespace WebDNA\Bundle\AppBundle\Entity\Base;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * Class Notification
 * @package WebDNA\Bundle\AppBundle\Entity\Base
 */
class Notification
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
     * @var integer
     *
     * @ORM\Column(name="object_id", type="bigint")
     */
    protected $objectId;

    /**
     * @var integer
     *
     * @ORM\Column(name="user_id", type="bigint")
     */
    protected $userId;

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
     * @Gedmo\Timestampable(on="update")
     * @ORM\Column(name="updated", type="datetime", nullable=true)
     */
    protected $updated;

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
     * Constructor
     */
    public function __construct()
    {
        $this->links = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Set type
     *
     * @param integer $type
     * @return Notification
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
     * Set objectId
     *
     * @param integer $objectId
     * @return Notification
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
     * Set userId
     *
     * @param integer $userId
     * @return Notification
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
     * Set status
     *
     * @param integer $status
     * @return Notification
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
     * Set created
     *
     * @param \DateTime $created
     * @return Notification
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
     * Set updated
     *
     * @param \DateTime $updated
     * @return Notification
     */
    public function setUpdated($updated)
    {
        $this->updated = $updated;

        return $this;
    }

    /**
     * Get updated
     *
     * @return \DateTime
     */
    public function getUpdated()
    {
        return $this->updated;
    }
}
