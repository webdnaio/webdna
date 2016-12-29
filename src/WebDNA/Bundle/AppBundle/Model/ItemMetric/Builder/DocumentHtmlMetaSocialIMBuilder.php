<?php

namespace WebDNA\Bundle\AppBundle\Model\ItemMetric\Builder;

use WebDNA\Bundle\AppBundle\Entity\ItemMetric;
use WebDNA\Bundle\AppBundle\Model\Crawler\CrawlerInterface;

/**
 * Class DocumentHtmlMetaSocialIMBuilder
 * @package WebDNA\Bundle\AppBundle\Model\ItemMetric\Builder
 */
class DocumentHtmlMetaSocialIMBuilder extends ItemMetricBuilder
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
        $itemMetric->setValueNumeric1($this->crawler->xpath("//*/meta[starts-with(@property, 'og:')]")->length);
        $itemMetric->setValueNumeric2($this->crawler->xpath("//*/meta[starts-with(@name, 'twitter:')]")->length);

        return $itemMetric;
    }

    /**
     * @return int
     */
    protected function getItemMetricType()
    {
        return ItemMetric::TYPE_DOCUMENT_HTML_META_SOCIAL_METRIC;
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
