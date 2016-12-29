<?php

namespace WebDNA\Bundle\AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\UniqueConstraint;

/**
 * WebsiteUserClassification
 *
 * @ORM\Table(
 * name="website_user_classification",
 * uniqueConstraints={
 *      @UniqueConstraint(
 *          name="website_user", columns={"website_id", "user_id"}
 *      )
 * })
 * @ORM\Entity(repositoryClass="\WebDNA\Bundle\AppBundle\Repository\WebsiteUserClassificationRepository")
 */
class WebsiteUserClassification extends Base\WebsiteUserClassification
{
    /**
     *
     */
    const CLASS_UNCLASSIFIED = 0;
    const CLASS_POSITIVE = 1;
    const CLASS_NEGATIVE = 2;
    const CLASS_UNKNOWN = 999;

    /**
     * @var array
     */
    public static $CLASSES = array(
        self::CLASS_UNCLASSIFIED => 'Unclassified',
        self::CLASS_POSITIVE => 'Positive',
        self::CLASS_NEGATIVE => 'Negative',
        self::CLASS_UNKNOWN => 'Unknown',
    );
}
