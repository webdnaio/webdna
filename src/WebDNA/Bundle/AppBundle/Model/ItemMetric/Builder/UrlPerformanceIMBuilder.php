<?php

namespace WebDNA\Bundle\AppBundle\Model\ItemMetric\Builder;

use Buzz\Client\Curl;
use WebDNA\Bundle\AppBundle\Entity\ItemMetric;

/**
 * Class UrlPerformanceIMBuilder
 * @package WebDNA\Bundle\AppBundle\Model\ItemMetric\Builder
 */
class UrlPerformanceIMBuilder extends ItemMetricBuilder
{
    /**
     * @param Website $website
     */
    public function __construct(Curl $curl)
    {
        $this->curl = $curl;
    }

    /**
     * @return ItemMetric
     */
    public function build()
    {
        $itemMetric = new ItemMetric();

        $itemMetric->setType($this->getItemMetricType());
        $itemMetric->setValueNumeric1($this->curl->getInfo(CURLINFO_TOTAL_TIME));
        $itemMetric->setValueNumeric2($this->curl->getInfo(CURLINFO_SPEED_DOWNLOAD));

        return $itemMetric;
    }

    /**
     * @return int
     */
    protected function getItemMetricType()
    {
        return ItemMetric::TYPE_URL_PERFORMANCE_METRIC;
    }

    /**
     * @param ItemMetric $metric
     * @return bool
     */
    public function isValid(ItemMetric $metric)
    {
        return $metric->getType() == $this->getItemMetricType() &&
            $metric->getValueNumeric1() >= 0 &&
            $metric->getValueNumeric2() >= 0;
    }
}
