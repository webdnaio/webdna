<?php

namespace WebDNA\Bundle\AppBundle\Repository\Service;

use Symfony\Component\DependencyInjection\ContainerInterface;
use WebDNA\Bundle\AppBundle\Entity\AnalysisProcess;
use WebDNA\Bundle\AppBundle\Entity\AnalysisProcessStats;
use WebDNA\Bundle\AppBundle\Entity\ItemAnalysis;
use WebDNA\Bundle\AppBundle\Repository\Interfaces\AnalysisProcessStatsRepositoryInterface;

/**
 * Class AnalysisProcessStatsService
 * @package WebDNA\Bundle\AppBundle\Repository\Service
 */
class AnalysisProcessStatsService
{
    /**
     * @var \WebDNA\Bundle\AppBundle\Repository\Interfaces\AnalysisProcessStatsRepositoryInterface
     */
    protected $analysisProcessStatsRepository;

    /**
     * Constructor.
     *
     * @param ContainerInterface $container
     * @param AnalysisProcessStatsRepositoryInterface $analysisProcessStatsRepository
     */
    public function __construct(
        ContainerInterface $container,
        AnalysisProcessStatsRepositoryInterface $analysisProcessStatsRepository
    ) {
        $this->container = $container;
        $this->analysisProcessStatsRepository = $analysisProcessStatsRepository;
    }

    /**
     * @return AnalysisProcessStats
     */
    public function create()
    {
        return new AnalysisProcessStats();
    }

    /**
     * @param $id
     * @return AnalysisProcessStats
     */
    public function find($id)
    {
        return $this->analysisProcessStatsRepository->find($id);
    }

    /**
     * @param AnalysisProcess $analysisProcess
     * @return AnalysisProcessStats
     */
    public function findByAnalysisProcess(AnalysisProcess $analysisProcess)
    {
        return $this->analysisProcessStatsRepository->findByAnalysisProcess($analysisProcess);
    }

    /**
     * @param AnalysisProcessStats $analysisProcessStats
     * @return mixed
     */
    public function save(AnalysisProcessStats $analysisProcessStats)
    {
        return $this->analysisProcessStatsRepository->save($analysisProcessStats);
    }

    /**
     * @param void
     * @return Int
     */
    public function countAll()
    {
        return $this->analysisProcessStatsRepository->countAll();
    }

    /**
     * @return mixed
     */
    public function compute()
    {
        return $this->analysisProcessStatsRepository->compute();
    }


    /**
     * @param AnalysisProcess $analysisProcess
     * @return mixed
     */
    public function getSummary(AnalysisProcess $analysisProcess)
    {
        return $this->analysisProcessStatsRepository->getSummary($analysisProcess);
    }

    /**
     * @param AnalysisProcess $analysisProcess
     * @return mixed
     */
    public function setStatsClassCounters(AnalysisProcess $analysisProcess)
    {
        $analysisProcessStats = $this
            ->findByAnalysisProcess($analysisProcess);

        $classCounters = $this->container->get('item_analyzes')
            ->countClasses($analysisProcess);

        $analysisProcessStats->setNegative($classCounters[ItemAnalysis::CLASS_NEGATIVE]);
        $analysisProcessStats->setPositive($classCounters[ItemAnalysis::CLASS_POSITIVE]);
        $analysisProcessStats->setSuspicious($classCounters[ItemAnalysis::CLASS_SUSPICIOUS]);

        return $this->save($analysisProcessStats);
    }

    /**
     * @param AnalysisProcess $analysisProcess
     * @param bool $skipIfExists
     * @return bool
     */
    public function setStatsCounters(AnalysisProcess $analysisProcess, $skipIfExists = false)
    {
        $twigMetricsExtension = $this->container->get('twig.metrics_extension');

        $analysisProcessStats = $this
            ->findByAnalysisProcess($analysisProcess);

        if ($skipIfExists === true && ($analysisProcessStats instanceof AnalysisProcessStats) === true) {
            //            return false;
        }

        if (($analysisProcessStats instanceof AnalysisProcessStats) === false) {
            $analysisProcessStats = $this->create();
            $analysisProcessStats->setAnalysisProcess($analysisProcess);
        }

        $itemMetricsSummary = $this->container->get('item_metrics')
            ->getSummary($analysisProcess);
        $analyzedDomainsCounter = $this->container->get('item_analyzes')
            ->countAnalyzedDomains($analysisProcess);
        $classCounters = $this->container->get('item_analyzes')
            ->countClasses($analysisProcess);
        $malwareCounter = $twigMetricsExtension
            ->getMalwareCounter($itemMetricsSummary);
        $linksFoundCounter = $twigMetricsExtension
            ->getLinksFoundCounter($itemMetricsSummary);
        $linksFollow = $twigMetricsExtension
            ->getFollowCounter($itemMetricsSummary);
        $linksNofollow = $twigMetricsExtension
            ->getNofollowCounter($itemMetricsSummary);
        $totalLinksAnalyzed = $this->container->get('item_analyzes')
            ->countByAnalysisProcess($analysisProcess);

        $analysisProcessStats->setAnalyzedDomains($analyzedDomainsCounter);
        $analysisProcessStats->setNegative($classCounters[ItemAnalysis::CLASS_NEGATIVE]);
        $analysisProcessStats->setPositive($classCounters[ItemAnalysis::CLASS_POSITIVE]);
        $analysisProcessStats->setSuspicious($classCounters[ItemAnalysis::CLASS_SUSPICIOUS]);
        $analysisProcessStats->setMalware($malwareCounter);
        $analysisProcessStats->setLinksFound($linksFoundCounter);
        $analysisProcessStats->setLinksFollow($linksFollow);
        $analysisProcessStats->setLinksNofollow($linksNofollow);
        $analysisProcessStats->setTotalPagesAnalyzed($totalLinksAnalyzed);

        $this->save($analysisProcessStats);
    }
}
