<?php

namespace WebDNA\Bundle\AppBundle\Repository\Interfaces;

use WebDNA\Bundle\AppBundle\Entity\AnalysisProcess;
use WebDNA\Bundle\AppBundle\Entity\AnalysisProcessStats;

/**
 * Interface AnalysisProcessStatsRepositoryInterface
 * @package WebDNA\Bundle\AppBundle\Repository\Interfaces
 */
interface AnalysisProcessStatsRepositoryInterface
{
    /**
     * @param $id
     * @return AnalysisProcessStats
     */
    public function find($id);

    /**
     * @param AnalysisProcessStats $analysisProcessStats
     * @return mixed
     */
    public function save(AnalysisProcessStats $analysisProcessStats);

    /**
     * @param void
     * @return Int
     */
    public function countAll();

    /**
     * @param AnalysisProcess $analysisProcess
     * @return mixed
     */
    public function getSummary(AnalysisProcess $analysisProcess);

    /**
     * @param AnalysisProcess $analysisProcess
     * @return AnalysisProcessStats
     */
    public function findByAnalysisProcess(AnalysisProcess $analysisProcess);
}
