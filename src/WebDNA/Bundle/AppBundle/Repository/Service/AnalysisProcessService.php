<?php

namespace WebDNA\Bundle\AppBundle\Repository\Service;

use WebDNA\Bundle\AppBundle\Entity\AnalysisProcess;
use WebDNA\Bundle\AppBundle\Entity\Base\Website;
use WebDNA\Bundle\AppBundle\Repository\Interfaces\AnalysisProcessRepositoryInterface;

/**
 * Class AnalysisProcessService
 * @package WebDNA\Bundle\AppBundle\Repository\Service
 */
class AnalysisProcessService
{
    /**
     * @var \WebDNA\Bundle\AppBundle\Repository\Interfaces\AnalysisProcessRepositoryInterface
     */
    protected $analysisProcessRepository;

    /**
     * @var
     */
    protected $paginatorSevice;

    /**
     * Constructor.
     *
     * @param AnalysisProcessRepositoryInterface $analysisProcessRepository
     * @param PaginatorService $paginatorService
     */
    public function __construct(
        AnalysisProcessRepositoryInterface $analysisProcessRepository,
        PaginatorService $paginatorService
    ) {
        $this->analysisProcessRepository = $analysisProcessRepository;
        $this->paginatorService = $paginatorService;
    }

    /**
     * @return AnalysisProcess
     */
    public function create()
    {
        return new AnalysisProcess();
    }

    /**
     * @param $id
     * @return AnalysisProcess
     */
    public function find($id)
    {
        return $this->analysisProcessRepository->find($id);
    }

    /**
     * @param integer $status
     * @return AnalysisProcess[]
     */
    public function findByStatus($status)
    {
        return $this->analysisProcessRepository->findByStatus($status);
    }

    /**
     * @param int $status
     * @param \DateTime $dateStart
     * @param \DateTime $dateEnd
     * @return AnalysisProcess[]
     */
    public function findByStatusWithDateRange($status, $dateStart, $dateEnd)
    {
        return $this->analysisProcessRepository->findByStatusWithDateRange($status, $dateStart, $dateEnd);
    }

    /**
     * @param int $pageNumber
     * @param int $limit
     * @return \Knp\Component\Pager\Pagination\PaginationInterface|null
     */
    public function findAll($pageNumber = 1, $limit = 100)
    {
        $offset = $this->paginatorService->getOffset($pageNumber, $limit);
        $query = $this->analysisProcessRepository->findAll($offset, $limit);

        return $this->paginatorService->getPaginator($query, $pageNumber, $limit);
    }

    /**
     * @param integer $websiteId
     * @return AnalysisProcess
     */
    public function findOneByWebsite($websiteId)
    {
        return $this->analysisProcessRepository->findOneByWebsite($websiteId);
    }

    /**
     * @param integer $websiteId
     * @param integer $status
     * @return AnalysisProcess
     */
    public function findOneByWebsiteAndStatus($websiteId, $status)
    {
        return $this->analysisProcessRepository->findOneByWebsiteAndStatus($websiteId, $status);
    }

    /**
     * @param integer $websiteId
     * @param int $limit
     * @param boolean $omitStatus
     * @return AnalysisProcess[]
     */
    public function findProcessesByWebsite($websiteId, $limit = null, $omitStatus = false)
    {
        return $this->analysisProcessRepository->findProcessesByWebsite($websiteId, $limit, $omitStatus);
    }

    /**
     * @param int $websiteId
     * @param boolean $omitStatus
     * @return array|null
     */
    public function countProcessesByWebsite($websiteId, $omitStatus = false)
    {
        return $this->analysisProcessRepository->countProcessesByWebsite($websiteId, $omitStatus);
    }

    /**
     * @param AnalysisProcess $analysisProcess
     * @return mixed
     */
    public function save(AnalysisProcess $analysisProcess)
    {
        return $this->analysisProcessRepository->save($analysisProcess);
    }

    /**
     * @param void
     * @return Int
     */
    public function countAll()
    {
        return $this->analysisProcessRepository->countAll();
    }

    /**
     * @param $websiteId
     * @return boolean
     */
    public function isAnyPendingByWebsite($websiteId)
    {
        return $this->analysisProcessRepository->isAnyPendingByWebsite($websiteId);
    }

    /**
     * @return \WebDNA\Bundle\AppBundle\Entity\AnalysisProcess[]
     */
    public function findAnalyzesToRepeat()
    {
        return $this->analysisProcessRepository->findAnalyzesToRepeat();
    }

    /**
     * @return \WebDNA\Bundle\AppBundle\Entity\AnalysisProcess[]
     */
    public function findUnRepeatable()
    {
        return $this->analysisProcessRepository->findUnRepeatable();
    }

    /**
     * @param AnalysisProcess $analysisProcess
     * @return AnalysisProcess
     */
    public function findPrevious(AnalysisProcess $analysisProcess)
    {
        return $this->analysisProcessRepository->findPrevious($analysisProcess);
    }

    /**
     * @param Website $website
     */
    public function clearRepeat(Website $website)
    {
        $processes = $website->getAnalysisProcesses();
        foreach ($processes as $process) {
            $process = $this->find($process->getId());
            $process->setRepeat(0);
            $this->save($process);
        }
    }

    /**
     * @param AnalysisProcess $analysisProcess
     */
    public function addPreviousAnalysisProcessInputs(AnalysisProcess &$analysisProcess)
    {
        $previousAnalysisProcess = $this->findPrevious($analysisProcess);

        if ($previousAnalysisProcess instanceof AnalysisProcess) {
            $inputs = $previousAnalysisProcess->getAnalysisProcessInputs();

            foreach ($inputs as $input) {
                if ($input->getAnalysisProcess()->getId() != $analysisProcess->getId()) {
                    $analysisProcess->addAnalysisProcessInput($input);
                }
            }
        }
    }
}
