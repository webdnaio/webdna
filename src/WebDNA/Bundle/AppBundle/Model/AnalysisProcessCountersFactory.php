<?php

namespace WebDNA\Bundle\AppBundle\Model;

use Predis\Client;
use WebDNA\Bundle\AppBundle\Entity\AnalysisProcess;

/**
 * Class AnalysisProcessCountersFactory
 * @package WebDNA\Bundle\AppBundle\Model
 */
class AnalysisProcessCountersFactory
{
    /**
     * @param Client $redis
     */
    public function __construct(Client $redis)
    {
        $this->redis = $redis;
    }

    /**
     * @param AnalysisProcess $analysisProcess
     * @return AnalysisProcessCounters
     */
    public function get(AnalysisProcess $analysisProcess)
    {
        return new AnalysisProcessCounters($this->redis, $analysisProcess);
    }
}
