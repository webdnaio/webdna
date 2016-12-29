<?php

namespace WebDNA\Bundle\AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * AnalysisProcessOutput
 *
 * @ORM\Table(name="analysis_process_output")
 * @ORM\Entity(repositoryClass="\WebDNA\Bundle\AppBundle\Repository\AnalysisProcessOutputRepository")
 */
class AnalysisProcessOutput extends Base\AnalysisProcessOutput
{
}
