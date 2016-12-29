<?php

namespace WebDNA\Bundle\UserBundle\Repository\Interfaces;

use WebDNA\Bundle\UserBundle\Entity\ContactData;

/**
 * Interface ContactDataRepositoryInterface
 * @package WebDNA\Bundle\UserBundle\Repository\Interfaces
 */
interface ContactDataRepositoryInterface
{
    /**
     * @param $id
     * @return User
     */
    public function find($id);

    /**
     * @param ContactData $contactData
     * @return mixed
     */
    public function save(ContactData $contactData);
}
