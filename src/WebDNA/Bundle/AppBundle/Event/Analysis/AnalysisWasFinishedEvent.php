<?php

namespace WebDNA\Bundle\AppBundle\Event\Analysis;

use Symfony\Component\DependencyInjection\ContainerInterface;
use WebDNA\Bundle\AppBundle\Entity\AnalysisProcess;

/**
 * Class AnalysisWasFinishedEvent
 * @package WebDNA\Bundle\AppBundle\Event\Analysis
 */
class AnalysisWasFinishedEvent extends AnalysisEvent
{
    /**
     * @var ContainerInterface
     */
    protected $container;

    /**
     * @var AnalysisProcess
     */
    protected $analysisProcess;

    /**
     * @var int
     */
    protected $stats;

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

    /**
     * @return \WebDNA\Bundle\UserBundle\Entity\User
     */
    public function getUser()
    {
        return $this->analysisProcess->getWebsite()->getUser();
    }

    /**
     * @return \WebDNA\Bundle\AppBundle\Entity\Website
     */
    public function getWebsite()
    {
        return $this->analysisProcess->getWebsite();
    }
}
