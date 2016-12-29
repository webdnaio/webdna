<?php

namespace WebDNA\Bundle\VerifierBundle\Repository\Interfaces;

use WebDNA\Bundle\VerifierBundle\Entity\Page;

/**
 * Interface PageRepositoryInterface
 * @package WebDNA\Bundle\VerifierBundle\Repository\Interfaces
 */
interface PageRepositoryInterface
{
    /**
     * @param $id
     * @return Page
     */
    public function find($id);

    /**
     * @param $url
     * @return mixed
     */
    public function findByUrl($url);

    /**
     * @param Page $page
     * @return mixed
     */
    public function save(Page $page);

    /**
     * @param void
     * @return Int
     */
    public function countAll();
}
