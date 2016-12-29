<?php

namespace WebDNA\Bundle\VerifierBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Website
 *
 * @ORM\Table(name="website")
 * @ORM\Entity(repositoryClass="WebDNA\Bundle\VerifierBundle\Repository\WebsiteRepository")
 */
class Website extends Base\Website
{
}
