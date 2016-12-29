<?php

namespace WebDNA\Bundle\AppBundle\Repository\Service;

use WebDNA\Bundle\AppBundle\Entity\WebsiteOwnershipConfirmation;
use WebDNA\Bundle\AppBundle\Repository\Interfaces\WebsiteOwnershipConfirmationRepositoryInterface;

/**
 * Class WebsiteOwnershipConfirmationService
 * @package WebDNA\Bundle\AppBundle\Repository\Service
 */
class WebsiteOwnershipConfirmationService
{
    /**
     * @var \WebDNA\Bundle\AppBundle\Repository\Interfaces\WebsiteOwnershipConfirmationRepositoryInterface
     */
    protected $websiteOwnershipConfirmationRepository;

    /**
     * Constructor.
     *
     * @param WebsiteOwnershipConfirmationRepositoryInterface $websiteOwnershipConfirmationRepository
     */
    public function __construct(WebsiteOwnershipConfirmationRepositoryInterface $websiteOwnershipConfirmationRepository)
    {
        $this->websiteOwnershipConfirmationRepository = $websiteOwnershipConfirmationRepository;
    }

    /**
     * @return WebsiteOwnershipConfirmation
     */
    public function create()
    {
        return new WebsiteOwnershipConfirmation();
    }

    /**
     * @param $id
     * @return WebsiteOwnershipConfirmation
     */
    public function find($id)
    {
        return $this->websiteOwnershipConfirmationRepository->find($id);
    }

    /**
     * @param WebsiteOwnershipConfirmation $websiteOwnershipConfirmation
     * @return mixed
     */
    public function save(WebsiteOwnershipConfirmation $websiteOwnershipConfirmation)
    {
        return $this->websiteOwnershipConfirmationRepository->save($websiteOwnershipConfirmation);
    }

    /**
     * @param void
     * @return Int
     */
    public function countAll()
    {
        return $this->websiteOwnershipConfirmationRepository->countAll();
    }
}
