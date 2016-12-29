<?php

namespace WebDNA\Bundle\AppBundle\Event\Analysis;

use WebDNA\Bundle\AppBundle\Entity\AnalysisProcess;

/**
 * Class AnalysisWasStartedEvent
 * @package WebDNA\Bundle\AppBundle\Event\Analysis
 */
class AnalysisWasStartedEvent extends AnalysisEvent
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
        parent::__construct($analysisProcess);

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
