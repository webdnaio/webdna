<?php

namespace WebDNA\Bundle\AppBundle\Repository\Service;

use WebDNA\Bundle\AppBundle\Entity\AnalysisProcessOutput;
use WebDNA\Bundle\AppBundle\Repository\Interfaces\AnalysisProcessOutputRepositoryInterface;

/**
 * Class AnalysisProcessOutputService
 * @package WebDNA\Bundle\AppBundle\Repository\Service
 */
class AnalysisProcessOutputService
{
    /**
     * @var \WebDNA\Bundle\AppBundle\Repository\Interfaces\AnalysisProcessOutputRepositoryInterface
     */
    protected $analysisProcessOutputRepository;

    /**
     * Constructor.
     *
     * @param AnalysisProcessOutputRepositoryInterface $analysisProcessOutputRepository
     */
    public function __construct(AnalysisProcessOutputRepositoryInterface $analysisProcessOutputRepository)
    {
        $this->analysisProcessOutputRepository = $analysisProcessOutputRepository;
    }

    /**
     * @return AnalysisProcessOutput
     */
    public function create()
    {
        return new AnalysisProcessOutput();
    }

    /**
     * @param $id
     * @return AnalysisProcessOutput
     */
    public function find($id)
    {
        return $this->analysisProcessOutputRepository->find($id);
    }

    /**
     * @param AnalysisProcessOutput $analysisProcessOutput
     * @return mixed
     */
    public function save(AnalysisProcessOutput $analysisProcessOutput)
    {
        return $this->analysisProcessOutputRepository->save($analysisProcessOutput);
    }

    /**
     * @param void
     * @return Int
     */
    public function countAll()
    {
        return $this->analysisProcessOutputRepository->countAll();
    }
}
