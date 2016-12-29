<?php

namespace WebDNA\Bundle\UserBundle\Repository\Interfaces;

use WebDNA\Bundle\UserBundle\Entity\User;

/**
 * Interface UserRepositoryInterface
 * @package WebDNA\Bundle\UserBundle\Repository\Interfaces
 */
interface UserRepositoryInterface
{
    /**
     * @param $id
     * @return User
     */
    public function find($id);

    /**
     * @param $username
     * @return mixed
     */
    public function findByUsername($username);

    /**
     * @param int $offset
     * @param int $limit
     * @return \Doctrine\ORM\AbstractQuery
     */
    public function findAll($offset = 0, $limit = 100);

    /**
     * @param $email
     * @return mixed
     */
    public function findByEmail($email);

    /**
     * @param string $role
     *
     * @return array
     */
    public function findByRole($role);

    /**
     * @param User $user
     * @return mixed
     */
    public function save(User $user);
}
