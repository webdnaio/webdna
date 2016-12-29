<?php

namespace WebDNA\Bundle\VerifierBundle\Repository\Interfaces;

use WebDNA\Bundle\VerifierBundle\Entity\Website;

/**
 * Interface WebsiteRepositoryInterface
 * @package WebDNA\Bundle\VerifierBundle\Repository\Interfaces
 */
interface WebsiteRepositoryInterface
{
    /**
     * @param $id
     * @return Website
     */
    public function find($id);

    /**
     * @param $name
     * @return mixed
     */
    public function findByName($name);

    /**
     * @param Website $Website
     * @return mixed
     */
    public function save(Website $Website);

    /**
     * @param void
     * @return Int
     */
    public function countAll();
}
