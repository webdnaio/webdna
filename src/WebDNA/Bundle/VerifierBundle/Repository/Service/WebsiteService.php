<?php

namespace WebDNA\Bundle\VerifierBundle\Repository\Service;

use WebDNA\Bundle\VerifierBundle\Entity\Website;
use WebDNA\Bundle\VerifierBundle\Repository\Interfaces\WebsiteRepositoryInterface;

/**
 * Class WebsiteService
 * @package WebDNA\Bundle\VerifierBundle\Repository\Service
 */
class WebsiteService
{
    /**
     * @var \WebDNA\Bundle\VerifierBundle\Repository\Interfaces\WebsiteRepositoryInterface
     */
    protected $websiteRepository;

    /**
     * Constructor.
     *
     * @param WebsiteRepositoryInterface $WebsiteRepository
     */
    public function __construct(WebsiteRepositoryInterface $WebsiteRepository)
    {
        $this->websiteRepository = $WebsiteRepository;
    }

    /**
     * @return Website
     */
    public function create()
    {
        return new Website();
    }

    /**
     * @param $id
     * @return Website
     */
    public function find($id)
    {
        return $this->websiteRepository->find($id);
    }

    /**
     * @param $name
     * @return Website
     */
    public function findByName($name)
    {
        return $this->websiteRepository->findByName($name);
    }

    /**
     * @param Website $Website
     * @return mixed
     */
    public function save(Website $Website)
    {
        return $this->websiteRepository->save($Website);
    }

    /**
     * @param void
     * @return Int
     */
    public function countAll()
    {
        return $this->websiteRepository->countAll();
    }

    /**
     * Finds unclassified Websites
     *
     * @param int $offset
     * @param int $limit
     * @return mixed
     */
    public function findUnclassified($offset = 0, $limit = 50)
    {
        return $this->websiteRepository->getUnclassified($offset, $limit);
    }
}
