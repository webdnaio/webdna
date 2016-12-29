<?php

namespace WebDNA\Bundle\AppBundle\Repository\Service;

use WebDNA\Bundle\AppBundle\Entity\Notification;
use WebDNA\Bundle\AppBundle\Repository\Interfaces\NotificationRepositoryInterface;

/**
 * Class NotificationService
 * @package WebDNA\Bundle\AppBundle\Repository\Service
 */
class NotificationService
{
    /**
     * @var \WebDNA\Bundle\AppBundle\Repository\Interfaces\NotificationRepositoryInterface
     */
    protected $notificationRepository;

    /**
     * Constructor.
     *
     * @param NotificationRepositoryInterface $notificationRepository
     */
    public function __construct(NotificationRepositoryInterface $notificationRepository)
    {
        $this->notificationRepository = $notificationRepository;
    }

    /**
     * @return Notification
     */
    public function create()
    {
        return new Notification();
    }

    /**
     * @param $id
     * @return Notification
     */
    public function find($id)
    {
        return $this->notificationRepository->find($id);
    }

    /**
     * @param Notification $notification
     * @return mixed
     */
    public function save(Notification $notification)
    {
        return $this->notificationRepository->save($notification);
    }

    /**
     * @param void
     * @return Int
     */
    public function countAll()
    {
        return $this->notificationRepository->countAll();
    }

    /**
     * @param int $objectId
     * @param int $type
     * @return mixed
     * @throws \Doctrine\ORM\NoResultException
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function getStatusByObjectAndType($objectId, $type)
    {
        return $this->notificationRepository->getStatusByObjectAndType($objectId, $type);
    }

    /**
     * @return Notification[]
     */
    public function getNotSentNotifications()
    {
        return $this->notificationRepository->getNotSentNotifications();
    }
}
