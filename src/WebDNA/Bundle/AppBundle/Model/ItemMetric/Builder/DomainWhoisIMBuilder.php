<?php

namespace WebDNA\Bundle\AppBundle\Model\ItemMetric\Builder;

use Novutec\WhoisParser\Parser;
use WebDNA\Bundle\AppBundle\Entity\ItemMetric;
use WebDNA\Bundle\AppBundle\Entity\Website;

/**
 * Class DomainWhoisIMBuilder
 * @package WebDNA\Bundle\AppBundle\Model\ItemMetric\Builder
 */
class DomainWhoisIMBuilder extends ItemMetricBuilder
{
    /**
     * @param Website $website
     */
    public function __construct(Website $website)
    {
        $this->website = $website;
    }

    /**
     * @return ItemMetric
     */
    public function build()
    {
        $parser = new Parser();
        $itemMetric = new ItemMetric();

        $data = $parser->lookup($this->website->getName());

        $itemMetric->setType($this->getItemMetricType());
        $itemMetric->setValueNumeric1(strtotime($this->normalizeDateTime($data->created)));
        $itemMetric->setValueNumeric2(strtotime($this->normalizeDateTime($data->changed)));
        $itemMetric->setValueNumeric3(strtotime($this->normalizeDateTime($data->expires)));
        $itemMetric->setValueText1(implode("\n", $data->rawdata));

        return $itemMetric;
    }

    /**
     * @param $datetime
     * @return mixed
     */
    protected function normalizeDateTime($datetime)
    {
        // Case: Y.m.d. H:i:s -> Y-m-d H:i:s
        $matches = array();

        if (preg_match('/^(\d{4})\.(\d{2})\.(\d{2}) (\d{2}):(\d{2}):(\d{2})$/', $datetime, $matches)) {
            $datetime = str_replace('.', '-', $datetime);
        }

        return $datetime;
    }

    /**
     * @return int
     */
    protected function getItemMetricType()
    {
        return ItemMetric::TYPE_DOMAIN_WHOIS_METRIC;
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
            strlen($metric->getValueText1()) > 0;
    }
}
