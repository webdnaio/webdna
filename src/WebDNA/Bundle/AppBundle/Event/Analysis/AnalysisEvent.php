<?php

namespace WebDNA\Bundle\AppBundle\Event\Analysis;

use Symfony\Component\EventDispatcher\Event;
use WebDNA\Bundle\AppBundle\Entity\AnalysisProcess;

/**
 * Class AnalysisEvent
 * @package WebDNA\Bundle\AppBundle\Event
 */
abstract class AnalysisEvent extends Event
{
    /**
     * @var AnalysisProcess
     */
    protected $analysisProcess;

    /**
     * @param AnalysisProcess $analysisProcess
     */
    public function __construct(AnalysisProcess $analysisProcess)
    {
        $this->analysisProcess = $analysisProcess;
    }

    /**
     * @return AnalysisProcess
     */
    public function getAnalysisProcess()
    {
        return $this->analysisProcess;
    }
}
