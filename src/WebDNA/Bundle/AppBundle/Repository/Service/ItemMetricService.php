<?php

namespace WebDNA\Bundle\AppBundle\Repository\Service;

use WebDNA\Bundle\AppBundle\Entity\AnalysisProcess;
use WebDNA\Bundle\AppBundle\Entity\ItemAnalysis;
use WebDNA\Bundle\AppBundle\Entity\ItemMetric;
use WebDNA\Bundle\AppBundle\Repository\Interfaces\ItemMetricRepositoryInterface;

/**
 * Class ItemMetricService
 * @package WebDNA\Bundle\AppBundle\Repository\Service
 */
class ItemMetricService
{
    /**
     * @var \WebDNA\Bundle\AppBundle\Repository\Interfaces\ItemMetricRepositoryInterface
     */
    protected $itemMetricRepository;

    /**
     * Constructor.
     *
     * @param ItemMetricRepositoryInterface $itemMetricRepository
     */
    public function __construct(ItemMetricRepositoryInterface $itemMetricRepository)
    {
        $this->itemMetricRepository = $itemMetricRepository;
    }

    /**
     * @return ItemMetric
     */
    public function create()
    {
        return new ItemMetric();
    }

    /**
     * @param $id
     * @return ItemMetric
     */
    public function find($id)
    {
        return $this->itemMetricRepository->find($id);
    }

    /**
     * @param ItemMetric $itemMetric
     * @return mixed
     */
    public function save(ItemMetric $itemMetric)
    {
        return $this->itemMetricRepository->save($itemMetric);
    }

    /**
     * @param void
     * @return Int
     */
    public function countAll()
    {
        return $this->itemMetricRepository->countAll();
    }

    /**
     * @param array $pageItemAnalyzes
     * @return array|null
     */
    public function getMetricsArray($pageItemAnalyzes)
    {
        $item_analysis_ids = [];
        if (!empty($pageItemAnalyzes)) {
            foreach ($pageItemAnalyzes as $item) {
                $item_analysis_ids[] = $item['itemAnalysisId'];
            }
        }
        if (!empty($item_analysis_ids)) {
            return $this->itemMetricRepository->getMetricsByItemsAnalysesIds($item_analysis_ids);
        } else {
            return false;
        }
    }

    /**
     * @param ItemAnalysis $itemAnalysis
     * @return array|null
     */
    public function getMetricsByItemAnalysisIdsTypeKey(ItemAnalysis $itemAnalysis)
    {
        return $this->itemMetricRepository->getMetricsByItemAnalysisIdsTypeKey([$itemAnalysis->getId()]);
    }

    /**
     * @param ItemAnalysis $itemAnalysis
     * @return array
     */
    public function getMetricsByItemAnalysis(ItemAnalysis $itemAnalysis)
    {
        return $this->itemMetricRepository->getMetricsByItemAnalysis($itemAnalysis);
    }

    /**
     * @param AnalysisProcess $analysisProcess
     * @return mixed
     */
    public function getSummary(AnalysisProcess $analysisProcess)
    {
        $itemMetricsSummary = [];
        $summary = $this->itemMetricRepository->getSummary($analysisProcess);
        if (!empty($summary)) {
            foreach ($summary as $item) {
                $itemMetricsSummary[$item['type']] = $item;
            }
        }
        return $itemMetricsSummary;
    }
}
