<?php

namespace WebDNA\Bundle\AppBundle\Repository\Interfaces;

use WebDNA\Bundle\AppBundle\Entity\Website;
use WebDNA\Bundle\UserBundle\Entity\User;

/**
 * Interface WebsiteRepositoryInterface
 * @package WebDNA\Bundle\AppBundle\Repository\Interfaces
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
     * @param $name
     * @param User $user
     * @return mixed
     */
    public function findUserWebsiteByName($name, User $user);

    /**
     * @return mixed
     * @param int $offset
     * @param int $limit
     */
    public function findAll($offset = 0, $limit = 100);

    /**
     * @param User $user
     * @return array
     */
    public function findAllUserWebsitesWithoutPagination(User $user);

    /**
     * @return array
     */
    public function findAllWithoutPagination();

    /**
     * @param User $user
     * @param int $offset
     * @param int $limit
     * @param Website $website
     * @return \Doctrine\ORM\Query
     */
    public function findUserWebsites(User $user, $offset = 0, $limit = 100, $website = null);

    /**
     * @param Website $website
     * @return mixed
     */
    public function save(Website $website);

    /**
     * @param void
     * @return Int
     */
    public function countAll();

    /**
     * @param string $name
     * @return string
     */
    public function parseName($name);

    /**
     * @param array $ids
     * @return mixed
     */
    public function findByIds(array $ids);
}
