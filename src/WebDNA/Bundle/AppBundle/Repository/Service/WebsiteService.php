<?php

namespace WebDNA\Bundle\AppBundle\Repository\Service;

use WebDNA\Bundle\AppBundle\Entity\Website;
use WebDNA\Bundle\AppBundle\Repository\Interfaces\WebsiteRepositoryInterface;
use WebDNA\Bundle\UserBundle\Entity\User;

/**
 * Class WebsiteService
 * @package WebDNA\Bundle\AppBundle\Repository\Service
 */
class WebsiteService
{

    /**
     * @var \WebDNA\Bundle\AppBundle\Repository\Interfaces\WebsiteRepositoryInterface
     */
    protected $websiteRepository;

    /**
     * @var
     */
    protected $paginatorService;

    /**
     * Constructor.
     *
     * @param WebsiteRepositoryInterface $websiteRepository
     * @param PaginatorService $paginatorService
     */
    public function __construct(
        WebsiteRepositoryInterface $websiteRepository,
        PaginatorService $paginatorService
    ) {
        $this->websiteRepository = $websiteRepository;
        $this->paginatorService = $paginatorService;
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
     * @param $name
     * @param User $user
     * @return mixed
     */
    public function findUserWebsiteByName($name, User $user)
    {
        return $this->websiteRepository->findUserWebsiteByName($name, $user);
    }

    /**
     * @param int $pageNumber
     * @param int $limit
     * @return \Knp\Component\Pager\Pagination\PaginationInterface|null
     */
    public function findAll($pageNumber = 1, $limit = 100)
    {
        $offset = $this->paginatorService->getOffset($pageNumber, $limit);
        $query = $this->websiteRepository->findAll($offset, $limit);

        return $this->paginatorService->getPaginator($query, $pageNumber, $limit);
    }

    /**
     * @return array
     */
    public function findAllWithoutPagination()
    {
        return $this->websiteRepository->findAllWithoutPagination();
    }

    /**
     * @param User $user
     * @return Website[]
     */
    public function findAllUserWebsitesWithoutPagination(User $user)
    {
        return $this->websiteRepository->findAllUserWebsitesWithoutPagination($user);
    }

    /**
     * @param User $user
     * @param int $pageNumber
     * @param int $limit
     * @param Website $website
     * @return mixed|null|object
     */
    public function findUserWebsites(User $user, $pageNumber = 1, $limit = 100, $website = null)
    {
        $offset = $this->paginatorService->getOffset($pageNumber, $limit);
        $query = $this->websiteRepository->findUserWebsites($user, $offset, $limit, $website);

        return $this->paginatorService->getPaginator($query, $pageNumber, $limit);
    }

    /**
     * @param Website $website
     * @return mixed
     */
    public function save(Website $website)
    {
        return $this->websiteRepository->save($website);
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
     * @param string $name
     * @return string
     */
    public function parseName($name)
    {
        return $this->websiteRepository->parseName($name);
    }

    /**
     * @param array $ids
     * @return mixed
     */
    public function findByIds(array $ids)
    {
        return $this->websiteRepository->findByIds($ids);
    }
}
