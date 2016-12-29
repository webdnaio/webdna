<?php

namespace WebDNA\Bundle\AppBundle\Repository\Interfaces;

use WebDNA\Bundle\AppBundle\Entity\Page;
use WebDNA\Bundle\AppBundle\Entity\PageMetric;
use WebDNA\Bundle\AppBundle\Entity\Website;

/**
 * Interface PageMetricRepositoryInterface
 * @package WebDNA\Bundle\AppBundle\Repository\Interfaces
 */
interface PageMetricRepositoryInterface
{
    /**
     * @param $id
     * @return PageMetric
     */
    public function find($id);

    /**
     * @param Website $website
     * @param Page $page
     * @return mixed
     */
    public function findByWebsitePage(Website $website, Page $page);

    /**
     * @param PageMetric $pageMetric
     * @return mixed
     */
    public function save(PageMetric $pageMetric);

    /**
     * @param void
     * @return Int
     */
    public function countAll();

    /**
     * @param PageMetric $pageMetric
     * @return mixed
     */
    public function remove(PageMetric $pageMetric);
}
