<?php

namespace WebDNA\Bundle\AppBundle\Repository\Interfaces;

use WebDNA\Bundle\AppBundle\Entity\Page;
use WebDNA\Bundle\AppBundle\Entity\AnalysisProcess;
use WebDNA\Bundle\AppBundle\Entity\Website;

/**
 * Interface PageRepositoryInterface
 * @package WebDNA\Bundle\AppBundle\Repository\Interfaces
 */
interface PageRepositoryInterface
{
    /**
     * @param $id
     * @return Page
     */
    public function find($id);

    /**
     * @param Page $page
     * @return mixed
     */
    public function save(Page $page);

    /**
     * @param void
     * @return Int
     */
    public function countAll();

    /**
     * @param Page $page
     * @param Website $website
     * @return object
     */
    public function findDetails(Page $page, Website $website);

    /**
     * @param AnalysisProcess $analysisProcess
     * @param array $excluded_websites_ids
     * @return array
     */
    public function getDisavowUrlsByAnalysisProcess(
        AnalysisProcess $analysisProcess,
        array $excluded_websites_ids = null
    );

    /**
     * @param array $items
     * @return array|null
     * @param int|null $class
     */
    public function getPages(array $items, $class = null);

    /**
     * @param int $websiteId
     * @return Page[]
     */
    public function findByWebsite($websiteId);

    /**
     * @param Website $website
     * @param string $url
     * @return Page|null
     */
    public function findByWebsiteAndUrl(Website $website, $url);

    /**
     * @param array $page_ids
     */
    public function getPagesByIds(array $page_ids);

    /**
     * @param Page $page
     * @param Website $website
     * @return mixed
     */
    public function getAnchor(Page $page, Website $website);
}
