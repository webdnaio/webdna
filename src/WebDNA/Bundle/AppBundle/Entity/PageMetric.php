<?php

namespace WebDNA\Bundle\AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\UniqueConstraint;

/**
 * PageMetric
 *
 * @ORM\Table(name="page_metric",
 * uniqueConstraints={
 *      @UniqueConstraint(
 *          name="website_page", columns={"website_id", "page_id"}
 *      )
 * })
 * @ORM\Entity(repositoryClass="WebDNA\Bundle\AppBundle\Repository\PageMetricRepository")
 */
class PageMetric extends Base\PageMetric
{
}
