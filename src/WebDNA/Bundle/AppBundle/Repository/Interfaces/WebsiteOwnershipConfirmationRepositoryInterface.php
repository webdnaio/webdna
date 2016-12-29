<?php

namespace WebDNA\Bundle\AppBundle\Repository\Interfaces;

use WebDNA\Bundle\AppBundle\Entity\WebsiteOwnershipConfirmation;

/**
 * Interface WebsiteOwnershipConfirmationRepositoryInterface
 * @package WebDNA\Bundle\AppBundle\Repository\Interfaces
 */
interface WebsiteOwnershipConfirmationRepositoryInterface
{
    /**
     * @param $id
     * @return WebsiteOwnershipConfirmation
     */
    public function find($id);

    /**
     * @param WebsiteOwnershipConfirmation $websiteOwnershipConfirmation
     * @return mixed
     */
    public function save(WebsiteOwnershipConfirmation $websiteOwnershipConfirmation);

    /**
     * @param void
     * @return Int
     */
    public function countAll();
}
