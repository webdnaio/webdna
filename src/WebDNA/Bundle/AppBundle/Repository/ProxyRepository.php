<?php

namespace WebDNA\Bundle\AppBundle\Repository;

use Doctrine\ORM\EntityRepository;
use WebDNA\Bundle\AppBundle\Entity\Proxy;
use WebDNA\Bundle\AppBundle\Repository\Interfaces\ProxyRepositoryInterface;

class ProxyRepository extends EntityRepository implements ProxyRepositoryInterface
{
    /**
     * @param Proxy $proxy
     */
    public function save(Proxy $proxy)
    {
        $this->_em->persist($proxy);

        return $this->_em->flush();
    }

    /**
     * @return mixed
     */
    public function deleteAll()
    {
        $q = $this->_em->createQuery('DELETE FROM WebDNAAppBundle:Proxy');

        return $q->execute();
    }
}
