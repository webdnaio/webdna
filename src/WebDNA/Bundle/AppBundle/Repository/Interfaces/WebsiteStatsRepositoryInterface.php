<?php

namespace WebDNA\Bundle\AppBundle\Repository\Interfaces;

use WebDNA\Bundle\AppBundle\Entity\Website;
use WebDNA\Bundle\AppBundle\Entity\WebsiteStats;

/**
 * Interface WebsiteStatsRepositoryInterface
 * @package WebDNA\Bundle\AppBundle\Repository\Interfaces
 */
interface WebsiteStatsRepositoryInterface
{
    /**
     * @param $id
     * @return WebsiteStats
     */
    public function find($id);

    /**
     * @param WebsiteStats $websiteStats
     * @return mixed
     */
    public function save(WebsiteStats $websiteStats);

    /**
     * @param void
     * @return Int
     */
    public function countAll();

    /**
     * @param Website $website
     * @return mixed
     */
    public function getSummary(Website $website);

    /**
     * @param Website $website
     * @return WebsiteStats
     */
    public function findByWebsite(Website $website);
}
