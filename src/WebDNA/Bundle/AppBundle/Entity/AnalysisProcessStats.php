<?php

namespace WebDNA\Bundle\AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * AnalysisProcessStats
 *
 * @ORM\Table(name="analysis_process_stats")
 * @ORM\Entity(repositoryClass="\WebDNA\Bundle\AppBundle\Repository\AnalysisProcessStatsRepository")
 */
class AnalysisProcessStats extends Base\AnalysisProcessStats
{
    public function __construct()
    {
        parent::__construct();
    }
}
