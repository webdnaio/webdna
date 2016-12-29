<?php

namespace WebDNA\Bundle\AppBundle\Repository\Service;

use WebDNA\Bundle\AppBundle\Entity\ItemAnalysis;
use WebDNA\Bundle\AppBundle\Entity\ItemMetric;
use WebDNA\Bundle\AppBundle\Entity\PageMetric;
use WebDNA\Bundle\AppBundle\Entity\Website;
use WebDNA\Bundle\AppBundle\Repository\Interfaces\PageMetricRepositoryInterface;
use WebDNA\Bundle\AppBundle\Entity\Page;

/**
 * Class PageMetricService
 * @package WebDNA\Bundle\AppBundle\Repository\Service
 */
class PageMetricService
{
    /**
     * @var PageMetricRepositoryInterface
     */
    protected $pageMetricRepository;

    /**
     * @var ItemMetricService
     */
    protected $itemMetricService;

    /**
     * @var PageService
     */
    protected $pageService;

    /**
     * Constructor.
     *
     * @param PageMetricRepositoryInterface $pageMetricRepository
     * @param ItemMetricService $itemMetricService
     * @param PageService $pageService
     */
    public function __construct(
        PageMetricRepositoryInterface $pageMetricRepository,
        ItemMetricService $itemMetricService,
        PageService $pageService
    ) {
        $this->pageMetricRepository = $pageMetricRepository;
        $this->itemMetricsService = $itemMetricService;
        $this->pageService = $pageService;
    }

    /**
     * @return PageMetric
     */
    public function create()
    {
        return new PageMetric();
    }

    /**
     * @param $id
     * @return PageMetric
     */
    public function find($id)
    {
        return $this->pageMetricRepository->find($id);
    }

    /**
     * @param Website $website
     * @param Page $page
     * @return mixed
     */
    public function findByWebsitePage(Website $website, Page $page)
    {
        return $this->pageMetricRepository->findByWebsitePage($website, $page);
    }

    /**
     * @param PageMetric $pageMetric
     * @return mixed
     */
    public function save(PageMetric $pageMetric)
    {
        return $this->pageMetricRepository->save($pageMetric);
    }

    /**
     * @param void
     * @return Int
     */
    public function countAll()
    {
        return $this->pageMetricRepository->countAll();
    }

    /**
     * @param PageMetric $pageMetric
     * @return mixed
     */
    public function remove(PageMetric $pageMetric)
    {
        return $this->pageMetricRepository->remove($pageMetric);
    }

    /**
     * @param ItemAnalysis $itemAnalysis
     * @return mixed|PageMetric
     */
    public function getMetricByItemAnalysis(
        ItemAnalysis $itemAnalysis
    ) {
        $analysisProcess = $itemAnalysis->getAnalysisProcess();
        $page = $this->pageService->find($itemAnalysis->getObjectId());

        $pageMetric = $this->findByWebsitePage(
            $analysisProcess->getWebsite(),
            $page
        );

        if (!$pageMetric) {
            $pageMetric = $this->create();
        }

        $metrics = $this->itemMetricsService->getMetricsByItemAnalysisIdsTypeKey($itemAnalysis);

        if (empty($metrics)) {
            return $pageMetric;
        }

        $pageMetric->setAnchor(
            $this->pageService->getAnchor(
                $page,
                $itemAnalysis->getAnalysisProcess()->getWebsite()
            )
        );

        $pageMetric->setItemAnalysis($itemAnalysis);

        $websiteItemMetrics = $this->itemMetricsService
            ->getMetricsByItemAnalysisIdsTypeKey($page->getWebsite()->getItemAnalysis());


        if (isset($websiteItemMetrics[ItemMetric::TYPE_URL_INTERNET_ARCHIVE_METRIC]['value_numeric_1'])) {
            $domainAge = new \DateTime();
            $domainAge->setTimestamp(
                (int)$websiteItemMetrics[ItemMetric::TYPE_URL_INTERNET_ARCHIVE_METRIC]['value_numeric_1']
            );
            $pageMetric->setDomainAge($domainAge);
        }

        if (isset($websiteItemMetrics[ItemMetric::TYPE_DOMAIN_MOZ_METRIC]['value_numeric_1'])) {
            $pageMetric->setDomainAuthority($websiteItemMetrics[ItemMetric::TYPE_DOMAIN_MOZ_METRIC]['value_numeric_1']);
        }

        $pageMetric->setPointingLinksCount(
            $metrics[ItemMetric::TYPE_DOCUMENT_LINKS_ATTR_FOLLOW_METRIC]['value_numeric_4']
            +
            $metrics[ItemMetric::TYPE_DOCUMENT_LINKS_ATTR_FOLLOW_METRIC]['value_numeric_5']
        );

        $pageMetric->setPointingLinksFollowCount(
            $metrics[ItemMetric::TYPE_DOCUMENT_LINKS_ATTR_FOLLOW_METRIC]['value_numeric_4']
        );

        $pageMetric->setTotalLinksOnSiteCount(
            $metrics[ItemMetric::TYPE_DOCUMENT_LINKS_ATTR_FOLLOW_METRIC]['value_numeric_1']
        );

        $pageMetric->setTotalLinksOnSiteFollowCount(
            $metrics[ItemMetric::TYPE_DOCUMENT_LINKS_ATTR_FOLLOW_METRIC]['value_numeric_2']
        );

        $pageMetric->setTotalLinksOnSiteNofollowCount(
            $metrics[ItemMetric::TYPE_DOCUMENT_LINKS_ATTR_FOLLOW_METRIC]['value_numeric_3']
        );

        $pageMetric->setPage($page);
        $pageMetric->setWebsite($analysisProcess->getWebsite());

        return $pageMetric;
    }
}
