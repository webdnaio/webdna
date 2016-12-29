<?php

namespace WebDNA\Bundle\VerifierBundle\Entity;

use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\ORM\Mapping as ORM;

/**
 * Rating
 *
 * @ORM\Table(name="rating")
 * @ORM\Entity(repositoryClass="WebDNA\Bundle\VerifierBundle\Repository\RatingRepository")
 */
class Rating extends Base\Rating
{
}
