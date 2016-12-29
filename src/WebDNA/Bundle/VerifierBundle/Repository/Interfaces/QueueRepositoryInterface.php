<?php

namespace WebDNA\Bundle\VerifierBundle\Repository\Interfaces;

use WebDNA\Bundle\VerifierBundle\Entity\Queue;
use WebDNA\Bundle\UserBundle\Entity\User;
use WebDNA\Bundle\VerifierBundle\Entity\Website;

/**
 * Interface WebsiteRepositoryInterface
 * @package WebDNA\Bundle\VerifierBundle\Repository\Interfaces
 */
interface QueueRepositoryInterface
{
    /**
     * @param $id
     * @return Queue
     */
//    public function find($id);

    /**
     * @param Queue $Queue
     * @return mixed
     */
    public function save(Queue $Queue);

    /**
     * @param Queue $Queue
     * @return mixed
     */
    public function delete(Queue $Queue);

    /**
     * @param void
     * @return Int
     */
    public function countAll();

    /**
     * @param User $user
     * @param Website $website
     * @param null $domain
     * @return mixed
     */
    public function get(User $user, Website $website = null, $domain = null);

    /**
     * @param User $user
     * @param int $limit
     * @return mixed
     */
    public function getWebsites(User $user, $limit = 10);

    /**
     * @param User $user
     * @param int $limit
     * @return mixed
     */
    public function getDomains(User $user, $limit = 10);
}
