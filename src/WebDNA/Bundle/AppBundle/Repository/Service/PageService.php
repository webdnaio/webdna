<?php

namespace WebDNA\Bundle\AppBundle\Repository\Service;

use WebDNA\Bundle\AppBundle\Entity\Page;
use WebDNA\Bundle\AppBundle\Entity\Website;
use WebDNA\Bundle\AppBundle\Entity\ItemAnalysis;
use WebDNA\Bundle\AppBundle\Repository\Interfaces\PageRepositoryInterface;
use WebDNA\Bundle\AppBundle\Entity\AnalysisProcess;

/**
 * Class PageService
 * @package WebDNA\Bundle\AppBundle\Repository\Service
 */
class PageService
{
    /**
     * @var \WebDNA\Bundle\AppBundle\Repository\Interfaces\PageRepositoryInterface
     */
    protected $pageRepository;

    /**
     * Constructor.
     *
     * @param PageRepositoryInterface $pageRepository
     */
    public function __construct(PageRepositoryInterface $pageRepository)
    {
        $this->pageRepository = $pageRepository;
    }

    /**
     * @return Page
     */
    public function create()
    {
        return new Page();
    }

    /**
     * @param $id
     * @return Page
     */
    public function find($id)
    {
        return $this->pageRepository->find($id);
    }

    /**
     * @param Page $page
     * @return mixed
     */
    public function save(Page $page)
    {
        return $this->pageRepository->save($page);
    }

    /**
     * @param void
     * @return Int
     */
    public function countAll()
    {
        return $this->pageRepository->countAll();
    }

    /**
     * @param int $websiteId
     * @return Page|null
     */
    public function findByWebsite($websiteId)
    {
        return $this->pageRepository->findByWebsite($websiteId);
    }

    /**
     * @param Website $website
     * @param string $url
     * @return Page|null
     */
    public function findByWebsiteAndUrl(Website $website, $url)
    {
        return $this->pageRepository->findByWebsiteAndUrl($website, $url);
    }

    /**
     * @param array $items
     * @return array|null
     * @param int|null $class
     */
    public function getPages(array $items, $class = null)
    {
        return $this->pageRepository->getPages($this->getPagesIdsByItems($items), $class);
    }

    /**
     * @param AnalysisProcess $analysisProcess
     * @param array $excluded_websites_ids
     * @return array
     */
    public function getDisavowUrlsByAnalysisProcess(
        AnalysisProcess $analysisProcess,
        array $excluded_websites_ids = null
    ) {
        return $this->pageRepository->getDisavowUrlsByAnalysisProcess($analysisProcess, $excluded_websites_ids);
    }

    /**
     * @param ItemAnalysis[] $items
     * @return array|null
     */
    protected function getPagesIdsByItems(array $items)
    {
        $pages_ids = [];
        if (!empty($items)) {
            foreach ($items as $item) {
                $pages_ids[] = $item->getObjectId();
            }
        }
        return $pages_ids;
    }

    /**
     * @param Page $page
     * @param Website $website
     * @return object
     */
    public function findDetails(Page $page, Website $website)
    {
        return $this->pageRepository->findDetails($page, $website);
    }

    /**
     * @param array $page_ids
     */
    public function getPagesByIds(array $page_ids)
    {
        return $this->pageRepository->getPagesByIds($page_ids);
    }

    /**
     * @param Page $page
     * @param Website $website
     * @return mixed
     */
    public function getAnchor(Page $page, Website $website)
    {
        return $this->pageRepository->getAnchor($page, $website);
    }
}
