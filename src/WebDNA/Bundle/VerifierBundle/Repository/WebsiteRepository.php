<?php

namespace WebDNA\Bundle\VerifierBundle\Repository;

use Doctrine\ORM\EntityRepository;
use WebDNA\Bundle\VerifierBundle\Entity\Website;
use WebDNA\Bundle\VerifierBundle\Repository\Interfaces\WebsiteRepositoryInterface;

/**
 * WebsiteRepository
 *
 */
class WebsiteRepository extends EntityRepository implements WebsiteRepositoryInterface
{
    /**
     * @param $name
     * @return Website
     */
    public function findByName($name)
    {
        return $this->findOneBy(array('name' => $name));
    }

    /**
     * @param $name
     * @param User $user
     * @return mixed|null|object
     */
    public function findUserWebsiteByName($name, User $user)
    {
        return $this->findOneBy(array('name' => $name, 'user' => $user));
    }

    /**
     * @param Website $website
     * @return mixed|void
     */
    public function save(Website $website)
    {
        $this->_em->persist($website);

        return $this->_em->flush();
    }

    /**
     * @param void
     * @return Int
     */
    public function countAll()
    {
        return $this->getEntityManager()
            ->createQuery('SELECT COUNT(w.id) FROM WebDNAAppBundle:Website w')
            ->getSingleScalarResult();
    }
}
