<?php

namespace WebDNA\Bundle\UserBundle\Repository\Service;

use WebDNA\Bundle\UserBundle\Entity\ContactData;
use WebDNA\Bundle\UserBundle\Repository\Interfaces\ContactDataRepositoryInterface;

/**
 * Class ContactDataService
 * @package WebDNA\Bundle\UserBundle\Repository\Service
 */
class ContactDataService
{
    /**
     * @var
     */
    protected $contactDataRepository;

    /**
     * Constructor.
     *
     * @param ContactDataRepositoryInterface $contactDataRepository
     */
    public function __construct(ContactDataRepositoryInterface $contactDataRepository)
    {
        $this->contactDataRepository = $contactDataRepository;
    }

    /**
     * @return ContactData
     */
    public function create()
    {
        return new ContactData();
    }

    /**
     * @param ContactData $contactData
     * @return mixed
     */
    public function save(ContactData $contactData)
    {
        return $this->contactDataRepository->save($contactData);
    }
}
