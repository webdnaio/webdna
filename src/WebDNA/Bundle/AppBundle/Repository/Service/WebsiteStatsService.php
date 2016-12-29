<?php

namespace WebDNA\Bundle\AppBundle\Repository\Service;

use WebDNA\Bundle\AppBundle\Entity\Website;
use WebDNA\Bundle\AppBundle\Entity\WebsiteStats;
use WebDNA\Bundle\AppBundle\Repository\Interfaces\WebsiteStatsRepositoryInterface;

/**
 * Class WebsiteStatsService
 * @package WebDNA\Bundle\AppBundle\Repository\Service
 */
class WebsiteStatsService
{
    /**
     * @var \WebDNA\Bundle\AppBundle\Repository\Interfaces\WebsiteStatsRepositoryInterface
     */
    protected $websiteStatsRepository;

    /**
     * Constructor.
     *
     * @param WebsiteStatsRepositoryInterface $websiteStatsRepository
     */
    public function __construct(WebsiteStatsRepositoryInterface $websiteStatsRepository)
    {
        $this->websiteStatsRepository = $websiteStatsRepository;
    }

    /**
     * @return WebsiteStats
     */
    public function create()
    {
        return new WebsiteStats();
    }

    /**
     * @param $id
     * @return WebsiteStats
     */
    public function find($id)
    {
        return $this->websiteStatsRepository->find($id);
    }

    /**
     * @param Website $website
     * @return WebsiteStats
     */
    public function findByWebsite(Website $website)
    {
        return $this->websiteStatsRepository->findByWebsite($website);
    }

    /**
     * @param WebsiteStats $websiteStats
     * @return mixed
     */
    public function save(WebsiteStats $websiteStats)
    {
        return $this->websiteStatsRepository->save($websiteStats);
    }

    /**
     * @param void
     * @return Int
     */
    public function countAll()
    {
        return $this->websiteStatsRepository->countAll();
    }
}
