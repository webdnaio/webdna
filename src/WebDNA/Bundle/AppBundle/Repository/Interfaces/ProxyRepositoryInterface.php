<?php

namespace WebDNA\Bundle\AppBundle\Repository\Interfaces;

use WebDNA\Bundle\AppBundle\Entity\Proxy;

/**
 * Interface ProxyRepositoryInterface
 * @package WebDNA\Bundle\AppBundle\Repository\Interfaces
 */
interface ProxyRepositoryInterface
{
    /**
     * @param $id
     * @return Proxy
     */
    public function find($id);

    /**
     * @param Proxy $proxy
     * @return mixed
     */
    public function save(Proxy $proxy);

    /**
     * @param array $criteria
     * @param array $orderBy
     * @param null $limit
     * @param null $offset
     * @return mixed
     */
    public function findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null);

    /**
     * @param array $criteria
     * @param array|null $orderBy
     *
     * @return object|null The entity instance or NULL if the entity can not be found.
     */
    public function findOneBy(array $criteria, array $orderBy = null);

    /**
     * @return mixed
     */
    public function deleteAll();
}
