<?php

namespace WebDNA\Bundle\UserBundle\Entity\Base;

use FOS\UserBundle\Model\User as BaseUser;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * User
 *
 */
class User extends BaseUser
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
     * @var \WebDNA\Bundle\UserBundle\Entity\ContactData
     *
     * @ORM\OneToMany(targetEntity="\WebDNA\Bundle\UserBundle\Entity\ContactData", mappedBy="user", cascade={"persist"})
     */
    protected $contactDatas;

    /**
     * Constructor
     */
    public function __construct()
    {
        parent::__construct();

        $this->contactDatas = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Add contactData
     *
     * @param \WebDNA\Bundle\UserBundle\Entity\ContactData $contactData
     * @return User
     */
    public function addContactData(\WebDNA\Bundle\UserBundle\Entity\ContactData $contactData)
    {
        $contactData->setUser($this);

        $this->contactDatas[] = $contactData;

        return $this;
    }

    /**
     * Remove contactData
     *
     * @param \WebDNA\Bundle\UserBundle\Entity\ContactData $contactData
     */
    public function removeContactData(\WebDNA\Bundle\UserBundle\Entity\ContactData $contactData)
    {
        $this->contactDatas->removeElement($contactData);
    }

    /**
     * Get itemAnalyzes
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getContactDatas()
    {
        return $this->contactDatas;
    }
}
