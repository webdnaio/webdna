<?php

namespace WebDNA\Bundle\AppBundle\Repository;

use Doctrine\ORM\EntityRepository;
use WebDNA\Bundle\AppBundle\Entity\Disavow;
use WebDNA\Bundle\AppBundle\Repository\Interfaces\DisavowRepositoryInterface;

class DisavowRepository extends EntityRepository implements DisavowRepositoryInterface
{
    /**
     * @param Disavow $link
     * @return mixed|void
     */
    public function save(Disavow $link)
    {
        $this->_em->persist($link);

        return $this->_em->flush();
    }

    /**
     * @return mixed
     */
    public function countAll()
    {
        return $this->getEntityManager()
            ->createQuery('SELECT COUNT(p.id) FROM WebDNAAppBundle:Disavow p')
            ->getSingleScalarResult();
    }
}
