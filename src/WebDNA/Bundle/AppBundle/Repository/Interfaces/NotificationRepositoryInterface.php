<?php

namespace WebDNA\Bundle\AppBundle\Repository\Interfaces;

use WebDNA\Bundle\AppBundle\Entity\Notification;

/**
 * Interface NotificationRepositoryInterface
 * @package WebDNA\Bundle\AppBundle\Repository\Interfaces
 */
interface NotificationRepositoryInterface
{
    /**
     * @param $id
     * @return Notification
     */
    public function find($id);

    /**
     * @param Notification $notification
     * @return mixed
     */
    public function save(Notification $notification);

    /**
     * @param void
     * @return Int
     */
    public function countAll();

    /**
     * @param int $objectId
     * @param int $type
     * @return mixed
     * @throws \Doctrine\ORM\NoResultException
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function getStatusByObjectAndType($objectId, $type);

    /**
     * @return array
     */
    public function getNotSentNotifications();
}
