<?php

namespace WebDNA\Bundle\AppBundle\Repository\Service;

use Gaufrette\Filesystem;
use WebDNA\Bundle\AppBundle\Entity\AnalysisProcessInput;
use WebDNA\Bundle\AppBundle\Repository\Interfaces\AnalysisProcessInputRepositoryInterface;

/**
 * Class AnalysisProcessInputService
 * @package WebDNA\Bundle\AppBundle\Repository\Service
 */
class AnalysisProcessInputService
{
    /**
     * @var \WebDNA\Bundle\AppBundle\Repository\Interfaces\AnalysisProcessInputRepositoryInterface
     */
    protected $analysisProcessInputRepository;

    /**
     * @var \Gaufrette\Filesystem
     */
    protected $fileService;


    /**
     * @param AnalysisProcessInputRepositoryInterface $analysisProcessInputRepository
     * @param Filesystem $fileService
     */
    public function __construct(
        AnalysisProcessInputRepositoryInterface $analysisProcessInputRepository,
        Filesystem $fileService
    ) {
        $this->analysisProcessInputRepository = $analysisProcessInputRepository;
        $this->fileService = $fileService;
    }

    /**
     * @return AnalysisProcessInput
     */
    public function create()
    {
        return new AnalysisProcessInput();
    }

    /**
     * @param $id
     * @return AnalysisProcessInput
     */
    public function find($id)
    {
        return $this->analysisProcessInputRepository->find($id);
    }

    /**
     * @param AnalysisProcessInput $analysisProcessInput
     * @return mixed
     */
    public function save(AnalysisProcessInput $analysisProcessInput)
    {
        return $this->analysisProcessInputRepository->save($analysisProcessInput);
    }

    /**
     * @param void
     * @return Int
     */
    public function countAll()
    {
        return $this->analysisProcessInputRepository->countAll();
    }

    /**
     * @param AnalysisProcessInput $analysisProcessInput
     * @param string $data
     * @return integer
     */
    public function saveData(AnalysisProcessInput $analysisProcessInput, $data)
    {
        return $this->fileService->write($analysisProcessInput->getPath(), $data);
    }

    /**
     * @param AnalysisProcessInput $analysisProcessInput
     * @return string
     */
    public function readData(AnalysisProcessInput $analysisProcessInput)
    {
        $data = $this->fileService->read($analysisProcessInput->getPath());

        return $data;
    }

    /**
     * @param string $data
     * @return string
     */
    public function readTextData($data)
    {
        // Replace cross-platform new lines character to one format
        return strtr($data, array(
            "\r\n" => "\n",
            "\r" => "\n",
        ));
    }
}
