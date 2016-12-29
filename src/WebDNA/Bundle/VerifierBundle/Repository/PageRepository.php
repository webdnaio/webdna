<?php

namespace WebDNA\Bundle\VerifierBundle\Repository;

use Doctrine\ORM\EntityRepository;
use WebDNA\Bundle\VerifierBundle\Entity\Page;
use WebDNA\Bundle\VerifierBundle\Repository\Interfaces\PageRepositoryInterface;

/**
 * PageRepository
 *
 */
class PageRepository extends EntityRepository implements PageRepositoryInterface
{
    /**
     * @param $url
     * @return mixed
     */
    public function findByUrl($url)
    {
        return $this->findOneBy(array('url' => $url));
    }

    /**
     * @param Page $page
     * @return mixed|void
     */
    public function save(Page $page)
    {
        $this->_em->persist($page);

        return $this->_em->flush();
    }

    /**
     * @param void
     * @return Int
     */
    public function countAll()
    {
        return $this->getEntityManager()
            ->createQuery('SELECT COUNT(p.id) FROM WebDNAVerifierBundle:Page p')
            ->getSingleScalarResult();
    }
}
