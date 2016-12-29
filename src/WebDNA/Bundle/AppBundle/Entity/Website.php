<?php

namespace WebDNA\Bundle\AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * Website
 * @ORM\Entity
 * @ORM\Table(name="website",
 *              uniqueConstraints={@ORM\UniqueConstraint(name="name_user_id_idx", columns={"name", "user_id"})}
 * )
 * @ORM\Entity(repositoryClass="\WebDNA\Bundle\AppBundle\Repository\WebsiteRepository")
 * @UniqueEntity(
 *     fields={"name", "user"}, groups={"domain"},
 *     message="Website already added",
 *     repositoryMethod="findByNameAndUser"
 * )
 */
class Website extends Base\Website
{
    /**
     *
     */
    const STATUS_NEW = 1;

    /**
     * Constructor
     */
    public function __construct()
    {
        parent::__construct();

        $this->setStatus(self::STATUS_NEW);
    }

    /**
     * {@inheritdoc}
     */
    public function setName($name)
    {
        return parent::setName(mb_strtolower($name, 'UTF-8'));
    }

    /**
     * Check if website has valid item analysis.
     *
     * @param int $days
     * @return bool
     */
    public function hasValidItemAnalysis($days = 14)
    {
        $result = false;
        $itemAnalysis = $this->getItemAnalysis();

        if ($itemAnalysis instanceof ItemAnalysis) {
            $result = $itemAnalysis->getCreated()->add(new \DateInterval('P' . $days . 'D')) >= new \DateTime() &&
                $itemAnalysis->getStatus() === ItemAnalysis::STATUS_COMPLETED;
        }

        return $result;
    }
}
