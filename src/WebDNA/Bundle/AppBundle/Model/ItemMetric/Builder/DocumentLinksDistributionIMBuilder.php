<?php

namespace WebDNA\Bundle\AppBundle\Model\ItemMetric\Builder;

use WebDNA\Bundle\AppBundle\Entity\ItemMetric;
use WebDNA\Bundle\AppBundle\Model\Crawler\CrawlerInterface;
use WebDNA\Bundle\AppBundle\Model\Crawler\Link\LinkList;

/**
 * Class DocumentLinksDistributionIMBuilder
 * @package WebDNA\Bundle\AppBundle\Model\ItemMetric\Builder
 */
class DocumentLinksDistributionIMBuilder extends ItemMetricBuilder
{
    /**
     *
     */
    const NUMBER_OF_RANGES = 5;

    /**
     * @param CrawlerInterface $crawler
     * @param LinkList $links
     */
    public function __construct(CrawlerInterface $crawler, LinkList $links)
    {
        $this->crawler = $crawler;
        $this->links = $links;
    }

    /**
     * @return ItemMetric
     */
    public function build()
    {
        $itemMetric = new ItemMetric();

        $ranges = array_fill_keys(range(1, self::NUMBER_OF_RANGES), null);
        $offset = 0;

        if ($this->links->count()) {
            // We use non-multibyte function for performance reasons.
            $body = strtolower($this->crawler->getContent());
            $bodyLength = strlen($body);
            $rangeLength = $bodyLength / self::NUMBER_OF_RANGES;

            foreach ($this->links as $i => $link) {
                $uri = $link->getUri();

                if ($uri) {
                    // We use non-multibyte and case-sensitive function for performance reasons.
                    // Instead case-insensitive searches, we rely on lower-cased  text.
                    $uriPosition = strpos($body, strtolower($uri), $offset);

                    if ($uriPosition !== false && $bodyLength) {
                        $range = (int)ceil($uriPosition / $rangeLength);
                        $ranges[$range] += 1;

                        $offset = $uriPosition;
                    }
                }
            }
        }

        $itemMetric->setType($this->getItemMetricType());

        // Save computed ranges count into separated entity fields.
        foreach ($ranges as $index => $linksCount) {
            $method = 'setValueNumeric' . $index;

            if (method_exists($itemMetric, $method)) {
                $itemMetric->{$method}($linksCount);
            }
        }

        return $itemMetric;
    }

    /**
     * @return int
     */
    protected function getItemMetricType()
    {
        return ItemMetric::TYPE_DOCUMENT_LINKS_DISTRIBUTION_METRIC;
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
        $metric->getValueNumeric4() >= 0 &&
        $metric->getValueNumeric5() >= 0;
    }
}
