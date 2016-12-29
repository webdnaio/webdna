<?php

namespace WebDNA\Bundle\VerifierBundle\Entity\Base;

use Doctrine\ORM\Mapping as ORM;

/**
 * Class Website
 * @package WebDNA\Bundle\VerifierBundle\Entity\Base
 */
class Website
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="bigint")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="CUSTOM")
     * @ORM\CustomIdGenerator(class="\WebDNA\Bundle\CommonBundle\Doctrine\ORM\Id\BigIntGenerator")
     */
    protected $id;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=128)
     */
    protected $name;

    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set name
     *
     * @param string $name
     * @return Website
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }
}
