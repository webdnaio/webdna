<?php

namespace WebDNA\Bundle\VerifierBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Queue
 *
 * @ORM\Table(name="queue")
 * @ORM\Entity(repositoryClass="WebDNA\Bundle\VerifierBundle\Repository\QueueRepository")
 */
class Queue extends Base\Queue
{
}
