<?php

namespace WebDNA\Bundle\AppBundle\Model\ItemMetric\Builder;

use Novutec\WhoisParser\Parser;
use SEOstats\Services\Mozscape;
use WebDNA\Bundle\AppBundle\ApiClient\UrlMetrics\MOZ;
use WebDNA\Bundle\AppBundle\Entity\ItemMetric;
use WebDNA\Bundle\AppBundle\Entity\Website;

/**
 * Class DomainMOZIMBuilder
 * @package WebDNA\Bundle\AppBundle\Model\ItemMetric\Builder
 */
class DomainMOZIMBuilder extends ItemMetricBuilder
{
    /**
     * @param MOZ $moz
     * @param Website $website
     */
    public function __construct(MOZ $moz, Website $website)
    {
        $this->moz = $moz;
        $this->website = $website;
    }

    /**
     * @return ItemMetric
     */
    public function build()
    {
        $metrics = $this->moz->getMetrics('http://' . $this->website->getName());

        $itemMetric = new ItemMetric();

        $itemMetric->setType($this->getItemMetricType());

        if (isset($metrics['domain_authority'])) {
            $itemMetric->setValueNumeric1($metrics['domain_authority']);
        }

        if (isset($metrics['mozrank_url'])) {
            $itemMetric->setValueNumeric2($metrics['mozrank_url']);
        }

        if (isset($metrics['links'])) {
            $itemMetric->setValueNumeric3($metrics['links']);
        }

        if (isset($metrics['external_equity_links'])) {
            $itemMetric->setValueNumeric4($metrics['external_equity_links']);
        }

        return $itemMetric;
    }

    /**
     * @return int
     */
    protected function getItemMetricType()
    {
        return ItemMetric::TYPE_DOMAIN_MOZ_METRIC;
    }

    /**
     * @param ItemMetric $metric
     * @return bool
     */
    public function isValid(ItemMetric $metric)
    {
        return $metric->getType() == $this->getItemMetricType() &&
            $metric->getValueNumeric1() >= 0 &&
            $metric->getValueNumeric2() >= 0 &&
            $metric->getValueNumeric3() >= 0 &&
            $metric->getValueNumeric4() >= 0;
    }
}
