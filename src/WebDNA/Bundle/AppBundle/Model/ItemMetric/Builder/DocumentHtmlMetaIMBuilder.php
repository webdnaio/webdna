<?php

namespace WebDNA\Bundle\AppBundle\Model\ItemMetric\Builder;

use WebDNA\Bundle\AppBundle\Entity\ItemMetric;
use WebDNA\Bundle\AppBundle\Model\Crawler\CrawlerInterface;

/**
 * Class DocumentHtmlMetaIMBuilder
 * @package WebDNA\Bundle\AppBundle\Model\ItemMetric\Builder
 */
class DocumentHtmlMetaIMBuilder extends ItemMetricBuilder
{
    /**
     * @param CrawlerInterface $crawler
     */
    public function __construct(CrawlerInterface $crawler)
    {
        $this->crawler = $crawler;
    }

    /**
     * @return ItemMetric
     */
    public function build()
    {
        $itemMetric = new ItemMetric();

        $itemMetric->setType($this->getItemMetricType());
        $itemMetric->setValueNumeric1(mb_strlen($this->crawler->getMetaTitle()));
        $itemMetric->setValueNumeric2(mb_strlen($this->crawler->getMetaDescription()));
        $itemMetric->setValueNumeric3(mb_strlen($this->crawler->getMetaKeywords()));

        return $itemMetric;
    }

    /**
     * @return int
     */
    protected function getItemMetricType()
    {
        return ItemMetric::TYPE_DOCUMENT_HTML_META_METRIC;
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
            $metric->getValueNumeric3() >= 0;
    }
}
