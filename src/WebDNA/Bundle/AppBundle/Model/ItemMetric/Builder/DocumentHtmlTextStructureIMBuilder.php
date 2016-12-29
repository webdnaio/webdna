<?php

namespace WebDNA\Bundle\AppBundle\Model\ItemMetric\Builder;

use WebDNA\Bundle\AppBundle\Entity\ItemMetric;
use WebDNA\Bundle\AppBundle\Model\Crawler\CrawlerInterface;

/**
 * Class DocumentHtmlTextStructureIMBuilder
 * @package WebDNA\Bundle\AppBundle\Model\ItemMetric\Builder
 */
class DocumentHtmlTextStructureIMBuilder extends ItemMetricBuilder
{
    /**
     * @param CrawlerInterface $crawler
     * @param $html
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

        // @todo: Think about non-multibyte functions for better performance.
        $htmlLength = mb_strlen($this->crawler->getContent());
        $plainTextLength = mb_strlen($this->crawler->extractPlainText());
        $ratio = ($htmlLength) ? ($plainTextLength * 100 / $htmlLength) : null;

        $itemMetric->setType($this->getItemMetricType());
        $itemMetric->setValueNumeric1($htmlLength);
        $itemMetric->setValueNumeric2($plainTextLength);
        $itemMetric->setValueNumeric3($ratio);

        return $itemMetric;
    }

    /**
     * @return int
     */
    protected function getItemMetricType()
    {
        return ItemMetric::TYPE_DOCUMENT_HTML_TEXT_STRUCTURE_METRIC;
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
