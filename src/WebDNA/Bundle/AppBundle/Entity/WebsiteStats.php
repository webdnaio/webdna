<?php

namespace WebDNA\Bundle\AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * WebsiteStats
 *
 * @ORM\Table(name="website_stats")
 * @ORM\Entity(repositoryClass="\WebDNA\Bundle\AppBundle\Repository\WebsiteStatsRepository")
 */
class WebsiteStats extends Base\WebsiteStats
{
    public function __construct()
    {
        parent::__construct();
    }
}
