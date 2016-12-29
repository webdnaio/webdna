<?php

namespace WebDNA\Bundle\AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * AnalysisProcess
 *
 * @ORM\Table(name="analysis_process", indexes={
 *  @ORM\Index(name="repeat_analysis", columns={"repeat_analysis"})
 * })
 * @ORM\Entity(repositoryClass="\WebDNA\Bundle\AppBundle\Repository\AnalysisProcessRepository")
 */
class AnalysisProcess extends Base\AnalysisProcess
{
    /**
     *
     */
    const TYPE_DISCOVER_DEMO = 1;
    const TYPE_DISCOVER = 2;

    /**
     *
     */
    const STATUS_PREPARING = 1;
    const STATUS_FETCHING_BACKLINKS = -11;
    const STATUS_READY_TO_PROCESS = 2;
    const STATUS_PROCESSING = 3;
    const STATUS_COMPLETED = 4;
    const STATUS_FAILED = 5;

    /**
     * @var array
     */
    public static $statusLabels = [
        self::STATUS_PREPARING => 'preparing',
        self::STATUS_FETCHING_BACKLINKS => 'fetching backlinks',
        self::STATUS_READY_TO_PROCESS => 'ready to process',
        self::STATUS_PROCESSING => 'processing',
        self::STATUS_COMPLETED => 'completed',
        self::STATUS_FAILED => 'failed',
    ];

    /**
     * Constructor
     */
    public function __construct()
    {
        parent::__construct();

        $this->setStatus(self::STATUS_PREPARING);
    }
}
