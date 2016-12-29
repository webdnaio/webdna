<?php

namespace WebDNA\Bundle\AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Page
 * @ORM\Table(name="page")
 * @ORM\Entity(repositoryClass="\WebDNA\Bundle\AppBundle\Repository\PageRepository")
 */
class Page extends Base\Page
{
    /**
     *
     */
    const STATUS_NEW = 1;
    const STATUS_PROCESSING = 2;
    const STATUS_PROCESSED = 3;
    const STATUS_FAILED = 4;

    /**
     *
     */
    const TYPE_EXTERNAL = 1;
}
