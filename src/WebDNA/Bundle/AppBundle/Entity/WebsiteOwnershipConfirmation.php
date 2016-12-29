<?php

namespace WebDNA\Bundle\AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * WebsiteOwnershipConfirmation
 *
 * @ORM\Table(name="website_ownership_confirmation")
 * @ORM\Entity(repositoryClass="\WebDNA\Bundle\AppBundle\Repository\WebsiteOwnershipConfirmationRepository")
 */
class WebsiteOwnershipConfirmation extends Base\WebsiteOwnershipConfirmation
{
    /**
     * Available methods.
     */
    const METHOD_FILE = 1;
    const METHOD_DNS = 2;
    const METHOD_META_TAG = 3;

    /**
     * Available statuses.
     */
    const STATUS_PENDING = 1;
    const STATUS_CONFIRMED = 2;
}
