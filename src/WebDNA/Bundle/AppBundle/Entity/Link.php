<?php

namespace WebDNA\Bundle\AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Link
 *
 * @ORM\Table(name="link")
 * @ORM\Entity(repositoryClass="\WebDNA\Bundle\AppBundle\Repository\LinkRepository")
 */
class Link extends Base\Link
{
    /**
     *
     */
    const STATUS_NEW = 1;

    /**
     *
     */
    const TYPE_TEXT = 1;
    const TYPE_IMAGE = 2;
}
