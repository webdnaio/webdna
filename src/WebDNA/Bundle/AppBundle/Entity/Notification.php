<?php

namespace WebDNA\Bundle\AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\UniqueConstraint;

/**
 * Notification
 *
 * @ORM\Table(
 * name="notification",
 * indexes={
        @ORM\Index(
 *          name="type_user", columns={"type", "user_id"}
 *      )
 * },
 * uniqueConstraints={
 *      @UniqueConstraint(
 *          name="object_type", columns={"object_id", "type"}
 *      )
 * })
 * @ORM\Entity(repositoryClass="\WebDNA\Bundle\AppBundle\Repository\NotificationRepository")
 */
class Notification extends Base\Notification
{
    const TYPE_ANALYSIS_COMPLETED = 1;

    const STATUS_OK = 1;
    const STATUS_FAILED = 2;
    const STATUS_NOT_SENT = 3;


    public function __construct()
    {
        parent::__construct();
    }
}
