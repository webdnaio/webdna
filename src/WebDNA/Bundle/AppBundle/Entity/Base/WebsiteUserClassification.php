<?php

namespace WebDNA\Bundle\AppBundle\Entity\Base;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * Class WebsiteUserClassification
 * @package WebDNA\Bundle\AppBundle\Entity\Base
 */
class WebsiteUserClassification
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
     * @var \WebDNA\Bundle\UserBundle\Entity\User
     *
     * @ORM\ManyToOne(targetEntity="\WebDNA\Bundle\UserBundle\Entity\User", cascade={"persist"})
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id", nullable=false)
     */
    protected $user;

    /**
     * @var integer
     *
     * @ORM\Column(name="class", type="smallint")
     */
    protected $class = 0;

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
     * Set user
     *
     * @param \WebDNA\Bundle\UserBundle\Entity\User $user
     * @return Website
     */
    public function setUser(\WebDNA\Bundle\UserBundle\Entity\User $user = null)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get user
     *
     * @return \WebDNA\Bundle\UserBundle\Entity\User
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * Set classification info.
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
     * Get classification info.
     *
     * @return integer
     */
    public function getClass()
    {
        return $this->class;
    }
}
