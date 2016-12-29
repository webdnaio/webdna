<?php

namespace WebDNA\Bundle\AppBundle\Repository\Service;

use WebDNA\Bundle\AppBundle\Entity\Proxy;
use WebDNA\Bundle\AppBundle\Repository\Interfaces\ProxyRepositoryInterface;

/**
 * Class ProxyService
 * @package WebDNA\Bundle\AppBundle\Repository\Service
 */
class ProxyService
{
    /**
     * @var ProxyRepositoryInterface
     */
    protected $proxyRepository;

    /**
     * Constructor.
     *
     * @param ProxyRepositoryInterface $proxyRepository
     */
    public function __construct(ProxyRepositoryInterface $proxyRepository)
    {
        $this->proxyRepository = $proxyRepository;
    }

    /**
     * @return Proxy
     */
    public function create()
    {
        return new Proxy();
    }

    /**
     * @param $id
     * @return Proxy
     */
    public function find($id)
    {
        return $this->proxyRepository->find($id);
    }

    /**
     * @param Proxy $proxy
     * @return mixed
     */
    public function save(Proxy $proxy)
    {
        return $this->proxyRepository->save($proxy);
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
        return $this->proxyRepository->findBy($criteria, $orderBy, $limit, $offset);
    }

    /**
     * @param array $criteria
     * @param array|null $orderBy
     *
     * @return object|null The entity instance or NULL if the entity can not be found.
     */
    public function findOneBy(array $criteria, array $orderBy = null)
    {
        return $this->proxyRepository->findOneBy($criteria, $orderBy);
    }

    /**
     * @return mixed
     */
    public function deleteAll()
    {
        return $this->proxyRepository->deleteAll();
    }
}
