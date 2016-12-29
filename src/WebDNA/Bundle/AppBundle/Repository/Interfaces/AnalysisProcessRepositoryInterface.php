<?php

namespace WebDNA\Bundle\AppBundle\Repository\Interfaces;

use Doctrine\DBAL\Query\QueryBuilder;
use WebDNA\Bundle\AppBundle\Entity\AnalysisProcess;

/**
 * Interface AnalysisProcessRepositoryInterface
 * @package WebDNA\Bundle\AppBundle\Repository\Interfaces
 */
interface AnalysisProcessRepositoryInterface
{
    /**
     * @param $id
     * @return AnalysisProcess
     */
    public function find($id);

    /**
     * @param $status
     * @return array of AnalysisProcess
     */
    public function findByStatus($status);

    /**
     * @param int $status
     * @param \DateTime $dateStart
     * @param \DateTime $dateEnd
     * @return AnalysisProcess[]
     */
    public function findByStatusWithDateRange($status, \DateTime $dateStart, \DateTime $dateEnd);

    /**
     * @param int $offset
     * @param int $limit
     * @param array $orderBy
     * @param array $criteria
     * @return mixed
     */
    public function findAll($offset = 0, $limit = 100, array $orderBy = null, array $criteria = null);

    /**
     * @param AnalysisProcess $analysisProcess
     * @return mixed
     */
    public function save(AnalysisProcess $analysisProcess);

    /**
     * @param void
     * @return Int
     */
    public function countAll();

    /**
     * @param integer $websiteId
     * @return array
     */
    public function findOneByWebsite($websiteId);

    /**
     * @param integer $websiteId
     * @param integer $status
     * @return AnalysisProcess
     */
    public function findOneByWebsiteAndStatus($websiteId, $status);

    /**
     * @param integer $websiteId
     * @param boolean $omitStatus
     * @return array
     */
    public function findProcessesByWebsite($websiteId, $omitStatus = false);

    /**
     * @param int $websiteId
     * @param boolean $omitStatus
     * @return array|null
     */
    public function countProcessesByWebsite($websiteId, $omitStatus = false);

    /**
     * @param $websiteId
     * @return boolean
     */
    public function isAnyPendingByWebsite($websiteId);

    /**
     * @return AnalysisProcess[]
     */
    public function findAnalyzesToRepeat();

    /**
     * @return AnalysisProcess[]
     */
    public function findUnRepeatable();

    /**
     * @param AnalysisProcess $analysisProcess
     * @return AnalysisProcess
     */
    public function findPrevious(AnalysisProcess $analysisProcess);
}
