<?php

namespace WebDNA\Bundle\AppBundle\Repository\Interfaces;

use WebDNA\Bundle\AppBundle\Entity\AnalysisProcessInput;

/**
 * Interface AnalysisProcessInputRepositoryInterface
 * @package WebDNA\Bundle\AppBundle\Repository\Interfaces
 */
interface AnalysisProcessInputRepositoryInterface
{
    /**
     * @param $id
     * @return AnalysisProcessInput
     */
    public function find($id);

    /**
     * @param AnalysisProcessInput $analysisProcessInput
     * @return mixed
     */
    public function save(AnalysisProcessInput $analysisProcessInput);

    /**
     * @param void
     * @return Int
     */
    public function countAll();
}
