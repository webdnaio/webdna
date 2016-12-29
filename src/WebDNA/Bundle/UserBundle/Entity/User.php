<?php

namespace WebDNA\Bundle\UserBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * User
 *
 * @ORM\Table(name="fos_user")
 * @ORM\Entity(repositoryClass="\WebDNA\Bundle\UserBundle\Repository\UserRepository")
 * @UniqueEntity("username")
 * @UniqueEntity("email")
 */
class User extends Base\User
{
    const ACCOUNT_TYPE_SYSTEM = 1;
    const ACCOUNT_TYPE_ANONYMOUS = 2;
    const ACCOUNT_TYPE_USER = 3;

    /**
     * @ORM\Column(name="account_type", type="smallint", nullable=false)
     */
    protected $accountType = self::ACCOUNT_TYPE_USER;

    /**
     * @ORM\Column(name="first_name", type="string", length=255, nullable=true)
     */
    protected $firstName;

    /**
     * @ORM\Column(name="last_name", type="string", length=255, nullable=true)
     */
    protected $lastName;

    /**
     * @ORM\Column(name="notifications_enabled", type="boolean", nullable=false)
     */
    protected $emailNotificationsEnabled = true;

    /**
     * @ORM\Column(name="created_at", type="datetime")
     * @Gedmo\Timestampable(on="create")
     */
    protected $createdAt;

    public function getAccountType()
    {
        return $this->accountType;
    }

    /**
     * @param $accountType
     */
    public function setAccountType($accountType)
    {
        $this->accountType = $accountType;
    }

    /**
     * @return bool
     */
    public function isSystemAccount()
    {
        return $this->getAccountType() == self::ACCOUNT_TYPE_SYSTEM;
    }

    /**
     * @return bool
     */
    public function isAnonymousAccount()
    {
        return $this->getAccountType() == self::ACCOUNT_TYPE_ANONYMOUS;
    }

    /**
     * @return bool
     */
    public function isUserAccount()
    {
        return $this->getAccountType() == self::ACCOUNT_TYPE_USER;
    }

    /**
     * @return mixed
     */
    public function getFirstname()
    {
        return $this->firstName;
    }

    /**
     * @param $firstName
     */
    public function setFirstname($firstName)
    {
        $this->firstName = $firstName;
    }

    /**
     * @return mixed
     */
    public function getLastname()
    {
        return $this->lastName;
    }

    /**
     * @param $lastName
     */
    public function setLastname($lastName)
    {
        $this->lastName = $lastName;
    }

    /**
     * {@inheritDoc}
     */
    public function setEmail($email)
    {
        parent::setEmail($email);
        parent::setUsername($email);
    }

    /**
     * @return mixed
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * @param $createdAt
     */
    public function setcreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;
    }

    /**
     * @return mixed
     */
    public function getEmailNotificationsEnabled()
    {
        return $this->emailNotificationsEnabled;
    }

    /**
     * @param $emailNotificationsEnabled
     */
    public function setEmailNotificationsEnabled($emailNotificationsEnabled)
    {
        $this->emailNotificationsEnabled = $emailNotificationsEnabled;
    }
}
