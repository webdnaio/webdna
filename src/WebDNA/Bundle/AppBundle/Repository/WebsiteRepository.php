<?php

namespace WebDNA\Bundle\AppBundle\Repository;

use Doctrine\ORM\QueryBuilder;
use Doctrine\ORM\EntityRepository;

use WebDNA\Bundle\AppBundle\Entity\Website;
use WebDNA\Bundle\AppBundle\Repository\Interfaces\WebsiteRepositoryInterface;
use WebDNA\Bundle\UserBundle\Entity\User;

/**
 * WebsiteRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
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
     * Helper method for findAll and findUserWebsites
     * @return string
     */
    private function getColumns()
    {
        return 'w.id, w.name,
            ap as analysisProcess,
            MAX(ap.id) AS ap_id,
            MIN(ap.status) AS ap_status,
            MAX(ap.created) AS ap_created,
            MAX(ap.finished) AS ap_finished,
            COUNT(ap.website) AS ap_counter';
    }

    /**
     * @param int $offset
     * @param int $limit
     * @param array $orderBy
     * @param array $criteria
     * @return mixed
     */
    public function findAll($offset = 0, $limit = 100, array $orderBy = null, array $criteria = null)
    {
        $qb = $this->getEntityManager()->createQueryBuilder()
            ->select($this->getColumns() . ', IDENTITY(w.user) as user_id, u.username, u.firstName, u.lastName')
            ->from('WebDNAAppBundle:Website', 'w')
            ->innerJoin('WebDNAAppBundle:AnalysisProcess', 'ap', 'WITH', 'w.id=ap.website')
            ->leftJoin('WebDNAUserBundle:User', 'u', 'WITH', 'u.id=w.user')
            ->where('u.accountType=:accountType')
            ->setParameter('accountType', User::ACCOUNT_TYPE_USER)
            ->setFirstResult($offset);

        if ($limit > 0) {
            $qb->setMaxResults($limit);
        }

        $count = $this->countAllWebsites(clone $qb);

        $qb->orderBy('ap.status', 'ASC')
            ->addOrderBy('ap.id', 'DESC')
            ->groupBy('ap.website');

        return $qb->getQuery()->setHint('knp_paginator.count', $count);
    }

    /**
     * @return array
     */
    public function findAllWithoutPagination()
    {
        $qb = $this->getEntityManager()->createQueryBuilder()->select('w')
            ->from('WebDNAAppBundle:Website', 'w');

        return $qb->getQuery()->getResult();
    }

    /**
     * @param QueryBuilder $qb
     * @return int
     */
    protected function countAllWebsites(QueryBuilder $qb)
    {
        $qb->select('COUNT(w.id)')
            ->setFirstResult(0)
            ->setMaxResults(1);

        return (int)$qb->getQuery()->getOneOrNullResult();
    }

    /**
     * @param User $user
     * @return array
     */
    public function findAllUserWebsitesWithoutPagination(User $user)
    {
        $qb = $this->getEntityManager()->createQueryBuilder()
            ->select('w')
            ->from('WebDNAAppBundle:Website', 'w')
            ->innerJoin('WebDNAAppBundle:AnalysisProcess', 'ap', 'WITH', 'w.id=ap.website')
            ->leftJoin('WebDNAUserBundle:User', 'u', 'WITH', 'u.id=w.user')
            ->where('w.user = :userId')
            ->andWhere('u.enabled=1')
            ->groupBy('w.id')
            ->setParameter('userId', $user->getId());

        return $qb->getQuery()->getResult();
    }

    /**
     * @param User $user
     * @param int $offset
     * @param int $limit
     * @param Website $website
     * @return \Doctrine\ORM\Query
     */
    public function findUserWebsites(User $user, $offset = 0, $limit = 100, $website = null)
    {
        $qb_max_ids = $this->getEntityManager()->createQueryBuilder()
            ->select('MAX(ap.id) AS max_id')
            ->from('WebDNAAppBundle:AnalysisProcess', 'ap')
            ->leftJoin('WebDNAAppBundle:Website', 'w', 'WITH', 'ap.website=w.id')
            ->leftJoin('WebDNAUserBundle:User', 'u', 'WITH', 'u.id=w.user')
            ->where('w.user = :userId')
            ->setParameter(
                'userId',
                $user->getId()
            )
            ->groupBy('ap.website');

        $qb = $this->getEntityManager()->createQueryBuilder()
            ->select($this->getColumns())
            ->from('WebDNAAppBundle:Website', 'w')
            ->innerJoin('WebDNAAppBundle:AnalysisProcess', 'ap', 'WITH', 'w.id=ap.website')
            ->leftJoin('WebDNAUserBundle:User', 'u', 'WITH', 'u.id=w.user')
            ->where('w.user = :userId')
            ->andWhere('u.enabled=1')
            ->andWhere('ap.id IN(:max_ids)')
            ->setFirstResult($offset)
            ->setMaxResults($limit)
            ->groupBy('w.id');

        $parameters = [
            'max_ids' => $qb_max_ids->getQuery()->getArrayResult(),
            'userId' => $user->getId()
        ];

        if (!is_null($website)) {
            $qb->andWhere('w.id = :websiteId');
            $parameters['websiteId'] = $website->getId();
        }

        $qb->setParameters(
            $parameters
        );

        $count = $this->countAllWebsites(clone $qb);

        $qb->orderBy('ap.created', 'DESC');

        return $qb->getQuery()->setHint('knp_paginator.count', $count);
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
     * @param string $name
     * @return string
     */
    public function parseName($name)
    {
        $scheme = parse_url($name, PHP_URL_SCHEME);

        if ($scheme !== null) {
            $name = parse_url($name, PHP_URL_HOST);
        }

        return strtolower($name);
    }

    /**
     * @param array $parameters
     * @return mixed
     */
    public function findByNameAndUser(array $parameters)
    {
        $qb = $this->getEntityManager()->createQueryBuilder()
            ->select('w.id')
            ->from('WebDNAAppBundle:Website', 'w')
            ->where('w.name = :name')
            ->setParameter('name', $this->parseName($parameters['name']))
            ->andWhere('w.user = :userId')
            ->setParameter('userId', $parameters['user'])
            ->setMaxResults(1);

        return $qb->getQuery()->getOneOrNullResult();
    }

    /**
     * @param void
     * @return Int
     */
    public function countAll()
    {
        return (int)$this->getEntityManager()
            ->createQuery('SELECT COUNT(w.id) FROM WebDNAAppBundle:Website w')
            ->getOneOrNullResult();
    }

    /**
     * @param array $ids
     * @return mixed
     */
    public function findByIds(array $ids)
    {
        $qb = $this->getEntityManager()->createQueryBuilder()
            ->select($this->getColumns())
            ->from('WebDNAAppBundle:Website', 'w')
            ->where('w.website IN (:website_ids)')
            ->setParameter('website_ids', $ids);

        return $qb->getQuery()->execute();
    }
}
