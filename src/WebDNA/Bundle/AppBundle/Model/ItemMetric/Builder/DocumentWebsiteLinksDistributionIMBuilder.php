<?php

namespace WebDNA\Bundle\AppBundle\Model\ItemMetric\Builder;

use WebDNA\Bundle\AppBundle\Entity\ItemMetric;
use WebDNA\Bundle\AppBundle\Entity\Website;
use WebDNA\Bundle\AppBundle\Model\Crawler\CrawlerInterface;
use WebDNA\Bundle\AppBundle\Model\Crawler\Link\LinkList;

/**
 * Class DocumentWebsiteLinksDistributionIMBuilder
 * @package WebDNA\Bundle\AppBundle\Model\ItemMetric\Builder
 */
class DocumentWebsiteLinksDistributionIMBuilder extends DocumentLinksDistributionIMBuilder
{
    /**
     * @param CrawlerInterface $crawler
     * @param LinkList $links
     * @param Website $website
     */
    public function __construct(CrawlerInterface $crawler, LinkList $links, Website $website)
    {
        $links = $links->filterDomainLinks($website->getName());

        parent::__construct($crawler, $links);
    }

    /**
     * @return int
     */
    protected function getItemMetricType()
    {
        return ItemMetric::TYPE_DOCUMENT_WEBSITE_LINKS_DISTRIBUTION_METRIC;
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
