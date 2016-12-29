<?php

namespace WebDNA\Bundle\AppBundle\Repository\Interfaces;

use WebDNA\Bundle\AppBundle\Entity\Link;

/**
 * Interface LinkRepositoryInterface
 * @package WebDNA\Bundle\AppBundle\Repository\Interfaces
 */
interface LinkRepositoryInterface
{
    /**
     * @param $id
     * @return Link
     */
    public function find($id);

    /**
     * @param Link $link
     * @return mixed
     */
    public function save(Link $link);

    /**
     * @param void
     * @return Int
     */
    public function countAll();
}
