<?php

namespace WebDNA\Bundle\AppBundle\Repository\Interfaces;

use WebDNA\Bundle\AppBundle\Entity\AnalysisProcessOutput;

/**
 * Interface AnalysisProcessOutputRepositoryInterface
 * @package WebDNA\Bundle\AppBundle\Repository\Interfaces
 */
interface AnalysisProcessOutputRepositoryInterface
{
    /**
     * @param $id
     * @return AnalysisProcessOutput
     */
    public function find($id);

    /**
     * @param AnalysisProcessOutput $analysisProcessOutput
     * @return mixed
     */
    public function save(AnalysisProcessOutput $analysisProcessOutput);

    /**
     * @param void
     * @return Int
     */
    public function countAll();
}
