<?php

namespace WebDNA\Bundle\AppBundle\Model\ItemMetric\Builder;

use WebDNA\Bundle\AppBundle\Entity\ItemMetric;
use WebDNA\Bundle\AppBundle\Entity\Website;
use WebDNA\Bundle\AppBundle\Model\Crawler\Link\LinkList;

/**
 * Class DocumentLinksAttrFollowIMBuilder
 * @package WebDNA\Bundle\AppBundle\Model\ItemMetric\Builder
 */
class DocumentLinksAttrFollowIMBuilder extends ItemMetricBuilder
{
    /**
     * @param LinkList $linkList
     * @param Website $website
     */
    public function __construct(LinkList $linkList, Website $website)
    {
        $this->linkList = $linkList;
        $this->website = $website;
    }

    /**
     * @return ItemMetric
     */
    public function build()
    {
        $itemMetric = new ItemMetric();

        $links = $this->linkList;

        $followLinks = $links->filterFollowLinks();
        $noFollowLinks = $links->filterNoFollowLinks();
        $websiteLinks = $links->filterDomainLinks($this->website->getName());
        $websiteFollowLinks = $websiteLinks->filterFollowLinks();
        $websiteNoFollowLinks = $websiteLinks->filterNoFollowLinks();

        $itemMetric->setType($this->getItemMetricType());
        $itemMetric->setValueNumeric1(count($links));
        $itemMetric->setValueNumeric2(count($followLinks));
        $itemMetric->setValueNumeric3(count($noFollowLinks));
        $itemMetric->setValueNumeric4(count($websiteFollowLinks));
        $itemMetric->setValueNumeric5(count($websiteNoFollowLinks));

        return $itemMetric;
    }

    /**
     * @return int
     */
    protected function getItemMetricType()
    {
        return ItemMetric::TYPE_DOCUMENT_LINKS_ATTR_FOLLOW_METRIC;
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
