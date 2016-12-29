<?php

namespace WebDNA\Bundle\AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Proxy
 *
 * @ORM\Table(name="proxy")
 * @ORM\Entity(repositoryClass="WebDNA\Bundle\AppBundle\Repository\ProxyRepository")
 */
class Proxy extends Base\Proxy
{
    /**
     * @return string
     */
    public function getAddress()
    {
        return $this->getHost() . ':' . $this->getPort();
    }
}
