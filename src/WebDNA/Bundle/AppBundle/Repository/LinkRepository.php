<?php

namespace WebDNA\Bundle\AppBundle\Repository;

use Doctrine\ORM\EntityRepository;

use WebDNA\Bundle\AppBundle\Entity\Link;
use WebDNA\Bundle\AppBundle\Repository\Interfaces\LinkRepositoryInterface;

/**
 * LinkRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class LinkRepository extends EntityRepository implements LinkRepositoryInterface
{
    /**
     * @param Link $link
     * @return mixed|void
     */
    public function save(Link $link)
    {
        $this->_em->persist($link);

        return $this->_em->flush();
    }

    /**
     * @param void
     * @return Int
     */
    public function countAll()
    {
        return $this->getEntityManager()
            ->createQuery('SELECT COUNT(l.id) FROM WebDNAAppBundle:Link l')
            ->getSingleScalarResult();
    }
}
