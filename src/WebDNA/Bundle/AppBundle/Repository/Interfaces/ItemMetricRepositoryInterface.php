<?php

namespace WebDNA\Bundle\AppBundle\Repository\Interfaces;

use WebDNA\Bundle\AppBundle\Entity\AnalysisProcess;
use WebDNA\Bundle\AppBundle\Entity\ItemMetric;
use WebDNA\Bundle\AppBundle\Entity\ItemAnalysis;

/**
 * Interface ItemMetricRepositoryInterface
 * @package WebDNA\Bundle\AppBundle\Repository\Interfaces
 */
interface ItemMetricRepositoryInterface
{
    /**
     * @param $id
     * @return ItemMetric
     */
    public function find($id);

    /**
     * @param ItemMetric $itemMetric
     * @return mixed
     */
    public function save(ItemMetric $itemMetric);

    /**
     * @param void
     * @return Int
     */
    public function countAll();

    /**
     * @param array $item_analysis_ids
     * @return array|null
     */
    public function getMetricsByItemsAnalysesIds(array $item_analysis_ids);

    /**
     * @param AnalysisProcess $analysisProcess
     * @return mixed
     */
    public function getSummary(AnalysisProcess $analysisProcess);

    /**
     * @param ItemAnalysis $itemAnalysis
     * @return array
     */
    public function getMetricsByItemAnalysis(ItemAnalysis $itemAnalysis);

    /**
     * @param array $item_analysis_ids
     * @return array
     */
    public function getMetricsByItemAnalysisIdsTypeKey(array $item_analysis_ids);
}
