<?php

namespace WebDNA\Bundle\VerifierBundle\Repository;

use Doctrine\ORM\EntityRepository;
use WebDNA\Bundle\VerifierBundle\Entity\Queue;
use WebDNA\Bundle\VerifierBundle\Entity\Website;
use WebDNA\Bundle\VerifierBundle\Repository\Interfaces\QueueRepositoryInterface;
use WebDNA\Bundle\UserBundle\Entity\User;

/**
 * QueueRepository
 *
 */
class QueueRepository extends EntityRepository implements QueueRepositoryInterface
{
    /**
     * @param Queue $queue
     * @return mixed|void
     */
    public function save(Queue $queue)
    {
        $this->_em->persist($queue);

        return $this->_em->flush();
    }

    /**
     * @param Queue $queue
     * @return mixed|void
     */
    public function delete(Queue $queue)
    {
        $this->_em->remove($queue);

        return $this->_em->flush();
    }

    /**
     * @param void
     * @return Int
     */
    public function countAll()
    {
        return $this->getEntityManager()
            ->createQuery('SELECT COUNT(q.user_id) FROM WebDNAVerifierBundle:Queue q')
            ->getSingleScalarResult();
    }

    /**
     * @param User $user
     * @param Website $website
     * @param null $domain
     * @return mixed
     */
    public function get(User $user, Website $website = null, $domain = null)
    {
        $builder = $this->getQueryBuilder($user, $website, $domain);

        $builder->setMaxResults(1);

        $results = $builder->getQuery()->getResult();

        return $results ? $results[0] : null;
    }

    /**
     * @param User $user
     * @param Website $website
     * @param null $domain
     * @return mixed
     */
    public function getAll(User $user, Website $website = null, $domain = null)
    {
        return $this->getQueryBuilder($user, $website, $domain)->getQuery()->getResult();
    }

    private function getQueryBuilder(User $user, Website $website = null, $domain = null)
    {
        $builder = $this->getEntityManager()->createQueryBuilder();

        $builder
            ->select('q')
            ->from('WebDNA\Bundle\VerifierBundle\Entity\Queue', 'q')
            ->join(
                'WebDNA\Bundle\VerifierBundle\Entity\Page',
                'p',
                \Doctrine\ORM\Query\Expr\Join::WITH,
                'q.pageId = p.id'
            )
            ->where('q.userId = :userId')
            ->setParameter('userId', $user->getId());

        if ($website) {
            $builder
                ->join(
                    'WebDNA\Bundle\VerifierBundle\Entity\Website',
                    'w',
                    \Doctrine\ORM\Query\Expr\Join::WITH,
                    'p.websiteId = w.id'
                )
                ->andWhere('w.id = :websiteId')
                ->setParameter('websiteId', $website->getId());
        }

        if ($domain) {
            $builder
                ->andWhere('p.domain = :domain')
                ->setParameter('domain', $domain);
        }
        
        return $builder;
    }

    /**
     * @param User $user
     * @param int $limit
     * @return mixed
     */
    public function getWebsites(User $user, $limit = 10)
    {
        $builder = $this->getEntityManager()->createQueryBuilder();

        $builder
            ->select('count(w.id) AS cnt, w')
            ->from('WebDNA\Bundle\VerifierBundle\Entity\Queue', 'q')
            ->join(
                'WebDNA\Bundle\VerifierBundle\Entity\Page',
                'p',
                \Doctrine\ORM\Query\Expr\Join::WITH,
                'q.pageId = p.id'
            )
            ->join(
                'WebDNA\Bundle\VerifierBundle\Entity\Website',
                'w',
                \Doctrine\ORM\Query\Expr\Join::WITH,
                'p.websiteId = w.id'
            )
            ->groupBy('w.id')
            ->where('q.userId = :userId')
            ->setParameter('userId', $user->getId())
            ->orderBy('cnt', 'DESC');

        $builder->setMaxResults($limit);

        return $builder->getQuery()->getResult();
    }

    /**
     * @param User $user
     * @param int $limit
     * @return mixed
     */
    public function getDomains(User $user, $limit = 10)
    {
        $builder = $this->getEntityManager()->createQueryBuilder();

        $builder
            ->select('count(p.domain) AS cnt, p.domain')
            ->from('WebDNA\Bundle\VerifierBundle\Entity\Queue', 'q')
            ->join(
                'WebDNA\Bundle\VerifierBundle\Entity\Page',
                'p',
                \Doctrine\ORM\Query\Expr\Join::WITH,
                'q.pageId = p.id'
            )
            ->groupBy('p.domain')
            ->where('q.userId = :userId')
            ->setParameter('userId', $user->getId())
            ->orderBy('cnt', 'DESC');

        $builder->setMaxResults($limit);

        return $builder->getQuery()->getResult();
    }
}
