<?php

namespace WebDNA\Bundle\VerifierBundle\Repository;

use Doctrine\ORM\EntityRepository;
use WebDNA\Bundle\VerifierBundle\Entity\Rating;
use WebDNA\Bundle\VerifierBundle\Repository\Interfaces\RatingRepositoryInterface;

/**
 * RatingRepository
 *
 */
class RatingRepository extends EntityRepository implements RatingRepositoryInterface
{
    /**
     * @param Rating $rating
     * @return mixed|void
     */
    public function save(Rating $rating)
    {
        $this->_em->persist($rating);

        return $this->_em->flush();
    }

    /**
     * @param void
     * @return Int
     */
    public function countAll()
    {
        return $this->getEntityManager()
            ->createQuery('SELECT COUNT(r.page_id) FROM WebDNAVerifierBundle:Rating r')
            ->getSingleScalarResult();
    }
}
