<?php

namespace WebDNA\Bundle\AppBundle\Model\ItemMetric\Builder;

use WebDNA\Bundle\AppBundle\Entity\ItemMetric;

/**
 * Class ItemMetricBuilder
 * @package WebDNA\Bundle\AppBundle\Model\ItemMetric\Builder
 */
abstract class ItemMetricBuilder
{
    /**
     * @param void
     * @return ItemMetric
     */
    abstract public function build();

    /**
     * @param ItemMetric $metric
     * @return bool
     */
    abstract public function isValid(ItemMetric $metric);
}
