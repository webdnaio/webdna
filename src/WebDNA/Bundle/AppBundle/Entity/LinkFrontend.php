<?php
/**
 * Created by PhpStorm.
 * User: tomek
 * Date: 14.03.2014
 * Time: 11:38
 */

namespace WebDNA\Bundle\AppBundle\Entity;

use Symfony\Component\Validator\Constraints as Assert;

class LinkFrontend
{

    /**
     * @Assert\Url()
     */
    public $name;
}
