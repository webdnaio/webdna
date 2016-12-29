<?php

namespace WebDNA\Bundle\AppBundle\Repository\Service;

use WebDNA\Bundle\AppBundle\Entity\Disavow;
use WebDNA\Bundle\AppBundle\Repository\Interfaces\DisavowRepositoryInterface;

/**
 * Class DisavowService
 * @package WebDNA\Bundle\AppBundle\Repository\Service
 */
class DisavowService
{
    /**
     * @var \WebDNA\Bundle\AppBundle\Repository\Interfaces\DisavowRepositoryInterface
     */
    protected $disavowRepository;

    /**
     * Constructor.
     *
     * @param DisavowRepositoryInterface $disavowRepository
     */
    public function __construct(DisavowRepositoryInterface $disavowRepository)
    {
        $this->disavowRepository = $disavowRepository;
    }

    /**
     * @return Disavow
     */
    public function create()
    {
        return new Disavow();
    }

    /**
     * @param $id
     * @return Disavow
     */
    public function find($id)
    {
        return $this->disavowRepository->find($id);
    }

    /**
     * @param Disavow $disavow
     * @return mixed
     */
    public function save(Disavow $disavow)
    {
        return $this->disavowRepository->save($disavow);
    }

    /**
     * @param void
     * @return Int
     */
    public function countAll()
    {
        return $this->disavowRepository->countAll();
    }

    /**
     * @param array $criteria
     * @param array $orderBy
     * @param null $limit
     * @param null $offset
     * @return mixed
     */
    public function findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
    {
        return $this->disavowRepository->findBy($criteria, $orderBy, $limit, $offset);
    }

    /**
     * @param array $criteria
     * @param array|null $orderBy
     *
     * @return object|null The entity instance or NULL if the entity can not be found.
     */
    public function findOneBy(array $criteria, array $orderBy = null)
    {
        return $this->disavowRepository->findOneBy($criteria, $orderBy);
    }
}
