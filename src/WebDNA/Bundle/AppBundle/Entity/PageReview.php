<?php

namespace WebDNA\Bundle\AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\UniqueConstraint;

/**
 * PageReview
 *
 * @ORM\Table(
 * name="page_review",
 * uniqueConstraints={
 *      @UniqueConstraint(
 *          name="page_website", columns={"page_id", "website_id"}
 *      )
 * })
 * @ORM\Entity(repositoryClass="\WebDNA\Bundle\AppBundle\Repository\PageReviewRepository")
 */
class PageReview extends Base\PageReview
{
}
