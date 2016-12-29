<?php

namespace WebDNA\Bundle\UserBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * User
 *
 * @ORM\Table(name="contact_data")
 * @ORM\Entity(repositoryClass="\WebDNA\Bundle\UserBundle\Repository\ContactDataRepository")
 */
class ContactData extends Base\ContactData
{
    /**
     * Type consts.
     */
    const TYPE_EMAIL = 1;
    const TYPE_PHONE = 2;
    const TYPE_FIRST_NAME = 4;
    const TYPE_LAST_NAME = 8;

    /**
     * Status consts.
     */
    const STATUS_PENDING = 1;
    const STATUS_CONFIRMED = 2;
}
