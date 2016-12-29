<?php

namespace WebDNA\Bundle\AppBundle\Repository\Service;

use WebDNA\Bundle\AppBundle\Entity\AnalysisProcess;
use WebDNA\Bundle\AppBundle\Entity\ItemAnalysis;
use WebDNA\Bundle\AppBundle\Repository\Interfaces\ItemAnalysisRepositoryInterface;
use WebDNA\Bundle\AppBundle\Entity\Website;

/**
 * Class ItemAnalysisService
 * @package WebDNA\Bundle\AppBundle\Repository\Service
 */
class ItemAnalysisService
{

    /**
     * @var \WebDNA\Bundle\AppBundle\Repository\Interfaces\ItemAnalysisRepositoryInterface
     */
    protected $itemAnalysisRepository;

    /**
     * @var
     */
    protected $pageService;

    /**
     * @var
     */
    protected $paginatorSevice;

    /**
     * Constructor
     *
     * @param ItemAnalysisRepositoryInterface $itemAnalysisRepository
     * @param PageService $pageService
     * @param PaginatorService $paginatorService
     */
    public function __construct(
        ItemAnalysisRepositoryInterface $itemAnalysisRepository,
        PageService $pageService,
        PaginatorService $paginatorService
    ) {
        $this->itemAnalysisRepository = $itemAnalysisRepository;
        $this->pageService = $pageService;
        $this->paginatorService = $paginatorService;
    }

    /**
     * @return ItemAnalysis
     */
    public function create()
    {
        return new ItemAnalysis();
    }

    /**
     * @param $id
     * @return ItemAnalysis
     */
    public function find($id)
    {
        return $this->itemAnalysisRepository->find($id);
    }

    /**
     * @param ItemAnalysis $itemAnalysis
     * @return mixed
     */
    public function save(ItemAnalysis $itemAnalysis)
    {
        return $this->itemAnalysisRepository->save($itemAnalysis);
    }

    /**
     * @param null|int $type
     * @return Int
     */
    public function countAll($type = null)
    {
        return $this->itemAnalysisRepository->countAll($type);
    }

    /**
     * @param AnalysisProcess $analysisProcess
     * @param array $criteria
     * @param int $pageNumber
     * @param int $limit
     * @param array $sort
     * @return \Knp\Bundle\PaginatorBundle\Pagination\SlidingPagination
     */
    public function findByAnalysisProcess(
        AnalysisProcess $analysisProcess,
        $criteria = array(),
        $pageNumber = 1,
        $limit = 25,
        array $sort = null
    ) {
        $offset = $this->paginatorService->getOffset($pageNumber, $limit);
        $query = $this->itemAnalysisRepository->findByAnalysisProcess(
            $analysisProcess,
            $criteria,
            $offset,
            $limit,
            $sort
        );

        return $this->paginatorService->getPaginator($query, $pageNumber, $limit);
    }

    /**
     * @param AnalysisProcess $analysisProcess
     * @param array $criteria
     * @param int $pageNumber
     * @param int $limit
     * @param array $sort
     * @param bool $withClassificationStats
     * @return \Knp\Bundle\PaginatorBundle\Pagination\SlidingPagination
     */
    public function findByAnalysisProcessWebsiteGroup(
        AnalysisProcess $analysisProcess,
        $criteria = array(),
        $pageNumber = 1,
        $limit = 25,
        array $sort = null,
        $withClassificationStats = false
    ) {
        $offset = $this->paginatorService->getOffset($pageNumber, $limit);
        $query = $this->itemAnalysisRepository->findByAnalysisProcessWebsiteGroup(
            $analysisProcess,
            $criteria,
            $offset,
            $limit,
            $sort,
            $withClassificationStats
        );

        return $this->paginatorService->getPaginator($query, $pageNumber, $limit);
    }

    /**
     * @param AnalysisProcess $analysisProcess
     * @return \Knp\Bundle\PaginatorBundle\Pagination\SlidingPagination
     */
    public function getWebsiteGroups(AnalysisProcess $analysisProcess)
    {
        return $this->findByAnalysisProcessWebsiteGroup(
            $analysisProcess,
            [],
            1,
            10,
            null,
            false
        );
    }

    /**
     * @param array $items
     * @return array
     */
    public function countItemClasses($items)
    {
        $ids = [];
        foreach ($items as $item) {
            $ids[] = $item['website_id'];
        }

        return $this->itemAnalysisRepository->countItemClasses($ids);
    }

    /**
     * @param AnalysisProcess $analysisProcess
     * @param array $criteria
     * @return \Doctrine\ORM\AbstractQuery|\Doctrine\ORM\Query
     */
    public function findByAnalysisProcessQuery(
        AnalysisProcess $analysisProcess,
        $criteria = array()
    ) {
        return $this->itemAnalysisRepository->findByAnalysisProcess($analysisProcess, $criteria, 0, 0);
    }

    /**
     * @param ItemAnalysis $itemAnalysis
     * @return mixed
     */
    public function getAnalyzedObject(ItemAnalysis $itemAnalysis)
    {
        $object = null;

        switch ($itemAnalysis->getType()) {
            case ItemAnalysis::TYPE_PAGE:
                $object = $this->pageService->find($itemAnalysis->getObjectId());

                break;
            default:
                throw new \LogicException('Unsupported object type');
        }

        return $object;
    }

    /**
     * @param AnalysisProcess $analysisProcess
     * @return int
     */
    public function countAnalyzedDomains(AnalysisProcess $analysisProcess)
    {
        return $this->itemAnalysisRepository->countAnalyzedDomains($analysisProcess);
    }

    /**
     * Finds unclassified item analyzes.
     *
     * @param int $type
     * @param int $offset
     * @param int $limit
     * @return mixed
     */
    public function findUnclassified($type, $offset = 0, $limit = 50)
    {
        return $this->itemAnalysisRepository->findUnclassified($type, $offset, $limit);
    }

    /**
     * Count analyzes by AnalysisProcess
     *
     * @param AnalysisProcess $analysisProcess
     * @return array|null
     */
    public function countClasses(AnalysisProcess $analysisProcess)
    {
        return $this->itemAnalysisRepository->countClasses($analysisProcess);
    }

    /**
     * @param AnalysisProcess $analysisProcess
     * @param Website $website
     * @param int $class
     * @return mixed
     */
    public function setWebsiteClassUser(AnalysisProcess $analysisProcess, Website $website, $class)
    {
        return $this->itemAnalysisRepository->setWebsiteClassUser($analysisProcess, $website, $class);
    }

    /**
     * @param AnalysisProcess $analysisProcess
     * @param Website $website
     * @return mixed
     */
    public function revertDefaultWebsiteClass(AnalysisProcess $analysisProcess, Website $website)
    {
        return $this->itemAnalysisRepository->revertDefaultWebsiteClass($analysisProcess, $website);
    }

    /**
     * @param AnalysisProcess $currentAnalysisProcess
     * @param AnalysisProcess $previousAnalysisProcess
     * @return array
     */
    public function getDiffUrls(AnalysisProcess $currentAnalysisProcess, AnalysisProcess $previousAnalysisProcess)
    {
        return $this->itemAnalysisRepository->getDiffUrls($currentAnalysisProcess, $previousAnalysisProcess);
    }

    /**
     * @param AnalysisProcess $analysisProcess
     * @return \Doctrine\ORM\AbstractQuery|\Doctrine\ORM\Query
     */
    public function countReviewedByAnalysisProcess(AnalysisProcess $analysisProcess)
    {
        return $this->countByAnalysisProcess($analysisProcess, true);
    }

    /**
     * @param AnalysisProcess $analysisProcess
     * @param $reviewed
     * @return int
     */
    public function countByAnalysisProcess(AnalysisProcess $analysisProcess, $reviewed = false)
    {
        return $this->itemAnalysisRepository->countByAnalysisProcess($analysisProcess, $reviewed);
    }

    /**
     * @param array $itemsIds
     * @return ItemAnalysis[]
     */
    public function getPagesIdsFromItemsIds(array $itemsIds)
    {
        return $this->itemAnalysisRepository->getPagesIdsFromItemsIds($itemsIds);
    }

    /**
     * @param AnalysisProcess $analysisProcess
     * @return mixed
     */
    public function getItemAnalyzesIds(AnalysisProcess $analysisProcess)
    {
        return $this->itemAnalysisRepository->getItemAnalyzesIds($analysisProcess);
    }
}
