<?php

namespace WebDNA\Bundle\AppBundle\Repository\Interfaces;

use WebDNA\Bundle\AppBundle\Entity\AnalysisProcess;
use WebDNA\Bundle\AppBundle\Entity\ItemAnalysis;
use WebDNA\Bundle\AppBundle\Entity\Website;

/**
 * Interface ItemAnalysisRepositoryInterface
 * @package WebDNA\Bundle\AppBundle\Repository\Interfaces
 */
interface ItemAnalysisRepositoryInterface
{
    /**
     * @param $id
     * @return ItemAnalysis
     */
    public function find($id);

    /**
     * @param ItemAnalysis $itemAnalysis
     * @return mixed
     */
    public function save(ItemAnalysis $itemAnalysis);

    /**
     * @param void
     * @return Int
     */
    public function countAll();

    /**
     * @param AnalysisProcess $analysisProcess
     * @param array $type
     * @param int $offset
     * @param int $limit
     * @param array $sort
     * @return mixed|void
     */
    public function findByAnalysisProcess(
        AnalysisProcess $analysisProcess,
        array $type = array(),
        $offset = 0,
        $limit = 25,
        array $sort = null
    );

    /**
     * @param AnalysisProcess $analysisProcess
     * @return int
     */
    public function countAnalyzedDomains(AnalysisProcess $analysisProcess);

    /**
     * Finds unclassified item analysis
     *
     * @param int $type
     * @param int $offset
     * @param int $limit
     * @return mixed
     */
    public function findUnclassified($type, $offset = 0, $limit = 50);


    /**
     * Count pages by $analysisProcess
     *
     * @param AnalysisProcess $analysisProcess
     * @return array|null
     */
    public function countClasses(AnalysisProcess $analysisProcess);

    /**
     * @param array $ids
     * @return array
     */
    public function countItemClasses($ids);

    /**
     * @param AnalysisProcess $analysisProcess
     * @param array $criteria
     * @param int $offset
     * @param int $limit
     * @param array $sort
     * @param bool $withClassificationStats
     * @return \Doctrine\ORM\AbstractQuery|\Doctrine\ORM\Query
     */
    public function findByAnalysisProcessWebsiteGroup(
        AnalysisProcess $analysisProcess,
        array $criteria = array(),
        $offset = 0,
        $limit = 25,
        array $sort = null,
        $withClassificationStats = false
    );

    /**
     * @param AnalysisProcess $analysisProcess
     * @param Website $website
     * @param int $class
     * @return mixed
     */
    public function setWebsiteClassUser(AnalysisProcess $analysisProcess, Website $website, $class);

    /**
     * @param AnalysisProcess $analysisProcess
     * @param Website $website
     * @return mixed
     */
    public function revertDefaultWebsiteClass(AnalysisProcess $analysisProcess, Website $website);

    /**
     * @param AnalysisProcess $currentAnalysisProcess
     * @param AnalysisProcess $previousAnalysisProcess
     * @return array
     */
    public function getDiffUrls(AnalysisProcess $currentAnalysisProcess, AnalysisProcess $previousAnalysisProcess);

    /**
     * @param AnalysisProcess $analysisProcess
     * @param $reviewed
     * @return int
     */
    public function countByAnalysisProcess(AnalysisProcess $analysisProcess, $reviewed = false);

    /**
     * @param array $itemsIds
     * @return ItemAnalysis[]
     */
    public function getPagesIdsFromItemsIds(array $itemsIds);

    /**
     * @param AnalysisProcess $analysisProcess
     * @return mixed
     */
    public function getItemAnalyzesIds(AnalysisProcess $analysisProcess);
}
