<?php

namespace WebDNA\Bundle\UserBundle\Repository;

use Doctrine\ORM\QueryBuilder;
use Doctrine\ORM\EntityRepository;

use WebDNA\Bundle\UserBundle\Entity\User;
use WebDNA\Bundle\UserBundle\Repository\Interfaces\UserRepositoryInterface;

/**
 * UserRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class UserRepository extends EntityRepository implements UserRepositoryInterface
{
    /**
     * @param $username
     * @return User
     */
    public function findByUsername($username)
    {
        return $this->findOneBy(array('username' => $username));
    }

    /**
     * @param int $offset
     * @param int $limit
     * @param array $orderBy
     * @param array $criteria
     * @return \Doctrine\ORM\AbstractQuery
     */
    public function findAll($offset = 0, $limit = 100, array $orderBy = null, array $criteria = null)
    {
        $qb = $this->getEntityManager()->createQueryBuilder()
            ->select('u')
            ->from('WebDNAUserBundle:User', 'u')
            ->where('u.accountType=:accountType')
            ->setParameter('accountType', User::ACCOUNT_TYPE_USER);

        $count = $this->countAllUsers(clone $qb);

        $qb->orderBy('u.id', 'DESC')
            ->setFirstResult($offset)
            ->setMaxResults($limit);

        return $qb->getQuery()->setHint('knp_paginator.count', $count);
    }

    /**
     * @param QueryBuilder $qb
     * @return int
     */
    protected function countAllUsers(QueryBuilder $qb)
    {
        $qb->select('COUNT(u.id)')
            ->setFirstResult(0)
            ->setMaxResults(1);

        return (int)$qb->getQuery()->getSingleScalarResult();
    }

    /**
     * @param $email
     * @return User
     */
    public function findByEmail($email)
    {
        return $this->findOneBy(array('email' => $email));
    }

    /**
     * @param string $role
     *
     * @return array
     */
    public function findByRole($role)
    {
        $qb = $this->_em->createQueryBuilder();
        $qb->select('u')
            ->from($this->_entityName, 'u')
            ->where('u.roles LIKE :roles')
            ->andWhere("u.roles NOT LIKE 'ROLE_ADMIN'")
            ->setParameter('roles', '%"'.$role.'"%');

        return $qb->getQuery()->getResult();
    }

    /**
     * @param User $user
     * @return mixed|void
     */
    public function save(User $user)
    {
        $this->_em->persist($user);

        return $this->_em->flush();
    }
}
