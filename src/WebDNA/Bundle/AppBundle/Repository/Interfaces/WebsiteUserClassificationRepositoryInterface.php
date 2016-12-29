<?php

namespace WebDNA\Bundle\AppBundle\Repository\Interfaces;

use WebDNA\Bundle\AppBundle\Entity\Website;
use WebDNA\Bundle\AppBundle\Entity\WebsiteUserClassification;
use WebDNA\Bundle\UserBundle\Entity\User;

/**
 * Interface WebsiteUserClassificationRepositoryInterface
 * @package WebDNA\Bundle\AppBundle\Repository\Interfaces
 */
interface WebsiteUserClassificationRepositoryInterface
{
    /**
     * @param $id
     * @return WebsiteUserClassification
     */
    public function find($id);

    /**
     * @param WebsiteUserClassification $websiteUserClassification
     * @return mixed
     */
    public function save(WebsiteUserClassification $websiteUserClassification);

    /**
     * @param void
     * @return Int
     */
    public function countAll();

    /**
     * @param Website $website
     * @param User $user
     * @return WebsiteUserClassification
     */
    public function findUserWebsiteClassification(Website $website, User $user);

    /**
     * @param WebsiteUserClassification $websiteUserClassification
     * @return mixed
     */
    public function remove(WebsiteUserClassification $websiteUserClassification);
}
