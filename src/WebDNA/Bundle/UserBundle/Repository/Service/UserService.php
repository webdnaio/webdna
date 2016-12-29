<?php

namespace WebDNA\Bundle\UserBundle\Repository\Service;

use WebDNA\Bundle\AppBundle\Repository\Service\PaginatorService;
use WebDNA\Bundle\UserBundle\Entity\User;
use WebDNA\Bundle\UserBundle\Repository\Interfaces\UserRepositoryInterface;

/**
 * Class UserService
 * @package WebDNA\Bundle\UserBundle\Repository\Service
 */
class UserService
{
    /**
     * @var
     */
    protected $userRepository;

    /**
     * Constructor.
     *
     * @param UserRepositoryInterface $userRepository
     * @param PaginatorService $paginatorService
     *
     */
    public function __construct(
        UserRepositoryInterface $userRepository,
        PaginatorService $paginatorService
    ) {
        $this->userRepository = $userRepository;
        $this->paginatorService = $paginatorService;
    }

    /**
     * @return User
     */
    public function create()
    {
        return new User();
    }

    /**
     * @param $id
     * @return User
     */
    public function find($id)
    {
        return $this->userRepository->find($id);
    }

    /**
     * @param $username
     * @return User
     */
    public function findByUsername($username)
    {
        return $this->userRepository->findByUsername($username);
    }

    /**
     * @param $email
     * @return mixed
     */
    public function findByEmail($email)
    {
        return $this->userRepository->findByEmail($email);
    }

    /**
     * @param int $pageNumber
     * @param int $limit
     * @return \Knp\Component\Pager\Pagination\PaginationInterface|null
     */
    public function findAll(
        $pageNumber = 1,
        $limit = 100
    ) {
        $offset = $this->paginatorService->getOffset($pageNumber, $limit);
        $query = $this->userRepository->findAll($offset, $limit);

        $paginator = $this->paginatorService->getPaginator($query, $pageNumber, $limit);

        return $paginator;
    }

    /**
     * @param string $role
     *
     * @return array
     */
    public function findByRole($role)
    {
        return $this->userRepository->findByRole($role);
    }

    /**
     * @param User $user
     * @return mixed
     */
    public function save(User $user)
    {
        return $this->userRepository->save($user);
    }
}
