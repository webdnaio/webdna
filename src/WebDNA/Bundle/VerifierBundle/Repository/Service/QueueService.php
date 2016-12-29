<?php

namespace WebDNA\Bundle\VerifierBundle\Repository\Service;

use WebDNA\Bundle\VerifierBundle\Entity\Queue;
use WebDNA\Bundle\VerifierBundle\Entity\Website;
use WebDNA\Bundle\VerifierBundle\Repository\Interfaces\QueueRepositoryInterface;
use WebDNA\Bundle\UserBundle\Entity\User;

/**
 * Class QueueService
 * @package WebDNA\Bundle\VerifierBundle\Repository\Service
 */
class QueueService
{
    /**
     * @var \WebDNA\Bundle\VerifierBundle\Repository\Interfaces\QueueRepositoryInterface
     */
    protected $queueRepository;

    /**
     * Constructor.
     *
     * @param QueueRepositoryInterface $queueRepository
     * @param PageService
     */
    public function __construct(QueueRepositoryInterface $queueRepository, PageService $pageService)
    {
        $this->queueRepository = $queueRepository;
        $this->pageService = $pageService;
    }

    /**
     * @return Queue
     */
    public function create()
    {
        return new Queue();
    }

    /**
     * @param $id
     * @return Queue
     */
    public function find($id)
    {
        return $this->queueRepository->find($id);
    }

    /**
     * @param Queue $queue
     * @return mixed
     */
    public function save(Queue $queue)
    {
        return $this->queueRepository->save($queue);
    }

    /**
     * @param Queue $queue
     * @return mixed
     */
    public function delete(Queue $queue)
    {
        return $this->queueRepository->delete($queue);
    }

    /**
     * @param void
     * @return Int
     */
    public function countAll()
    {
        return $this->queueRepository->countAll();
    }

    /**
     * @param User      $user
     * @param Website   $website
     * @param null      $domain
     * @return mixed
     */
    public function get(User $user, Website $website = null, $domain = null)
    {
        $queueItem = $this->queueRepository->get($user, $website, $domain);

        return ($queueItem instanceof Queue)
            ? $this->pageService->find($queueItem->getPageId())
            : null;
    }

    /**
     * @param User      $user
     * @param Website   $website
     * @param null      $domain
     * @return mixed
     */
    public function getAll(User $user, Website $website = null, $domain = null)
    {
        return $this->queueRepository->getAll($user, $website, $domain);
    }

    /**
     * @param User $user
     * @param int $limit
     * @return mixed
     */
    public function getWebsites(User $user, $limit = 10)
    {
        return $this->queueRepository->getWebsites($user, $limit);
    }

    /**
     * @param User $user
     * @param int $limit
     * @return mixed
     */
    public function getDomains(User $user, $limit = 10)
    {
        return $this->queueRepository->getDomains($user, $limit);
    }
}
