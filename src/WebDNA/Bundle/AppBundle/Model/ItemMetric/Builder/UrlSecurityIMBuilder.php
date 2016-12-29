<?php

namespace WebDNA\Bundle\AppBundle\Model\ItemMetric\Builder;

use Buzz\Client\Curl;
use WebDNA\Bundle\AppBundle\Entity\ItemMetric;

/**
 * Class UrlSecurityIMBuilder
 * @package WebDNA\Bundle\AppBundle\Model\ItemMetric\Builder
 */
class UrlSecurityIMBuilder extends ItemMetricBuilder
{
    /**
     * @param Website $website
     */
    public function __construct($googleSafeBrowsing, $url)
    {
        $this->googleSafeBrowsing = $googleSafeBrowsing;
        $this->url = $url;
    }

    /**
     * @return ItemMetric
     */
    public function build()
    {
        $itemMetric = new ItemMetric();

        $itemMetric->setType(ItemMetric::TYPE_URL_SECURITY_METRIC);
        $itemMetric->setValueNumeric1((int)$this->googleSafeBrowsing->doLookup($this->url));

        return $itemMetric;
    }

    /**
     * @return int
     */
    protected function getItemMetricType()
    {
        return ItemMetric::TYPE_URL_SECURITY_METRIC;
    }

    /**
     * @param ItemMetric $metric
     * @return bool
     */
    public function isValid(ItemMetric $metric)
    {
        return $metric->getType() == $this->getItemMetricType() &&
            $metric->getValueNumeric1() >= 0;
    }
}
