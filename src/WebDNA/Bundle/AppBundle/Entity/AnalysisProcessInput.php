<?php

namespace WebDNA\Bundle\AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * AnalysisProcessInput
 *
 * @ORM\Table(name="analysis_process_input")
 * @ORM\Entity(repositoryClass="\WebDNA\Bundle\AppBundle\Repository\AnalysisProcessInputRepository")
 */
class AnalysisProcessInput extends Base\AnalysisProcessInput
{
    /**
     *
     */
    const TYPE_FILE = 1;

    /**
     *
     */
    const STATUS_NEW = 1;
}
