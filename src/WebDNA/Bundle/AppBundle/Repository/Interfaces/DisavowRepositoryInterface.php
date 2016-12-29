<?php

namespace WebDNA\Bundle\AppBundle\Repository\Interfaces;

use WebDNA\Bundle\AppBundle\Entity\Disavow;

/**
 * Interface DisavowRepositoryInterface
 * @package WebDNA\Bundle\AppBundle\Repository\Interfaces
 */
interface DisavowRepositoryInterface
{
    /**
     * @param $id
     * @return Disavow
     */
    public function find($id);

    /**
     * @param Disavow $disavow
     * @return mixed
     */
    public function save(Disavow $disavow);

    /**
     * @param void
     * @return Int
     */
    public function countAll();

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
}
