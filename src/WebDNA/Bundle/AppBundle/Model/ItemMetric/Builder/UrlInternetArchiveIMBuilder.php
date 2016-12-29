<?php

namespace WebDNA\Bundle\AppBundle\Model\ItemMetric\Builder;

use WebDNA\Bundle\AppBundle\ApiClient\WebArchive\WebArchive;
use WebDNA\Bundle\AppBundle\Entity\ItemMetric;
use WebDNA\Bundle\AppBundle\Entity\Website;

/**
* Class UrlInternetArchiveIMBuilder
 * @package WebDNA\Bundle\AppBundle\Model\ItemMetric\Builder
 */
class UrlInternetArchiveIMBuilder extends ItemMetricBuilder
{
    /**
     * @param WebArchive $webArchiveClient
     * @param Website $website
     */
    public function __construct(WebArchive $webArchiveClient, Website $website)
    {
        $this->webArchiveClient = $webArchiveClient;
        $this->website = $website;
    }

    /**
     * @return ItemMetric
     */
    public function build()
    {
        $itemMetric = new ItemMetric();

        $snapshots = $this->webArchiveClient->getSnapshots($this->website->getName());

        $itemMetric->setType($this->getItemMetricType());

        // Collect the oldest snapshot for given URL, if exists.
        if ($snapshots) {
            $snapshot = $snapshots[0];

            $itemMetric->setValueNumeric1($snapshot->getDate()->getTimestamp());
            $itemMetric->setValueText1($snapshot->getUrl());
        }

        return $itemMetric;
    }

    /**
     * @return int
     */
    protected function getItemMetricType()
    {
        return ItemMetric::TYPE_URL_INTERNET_ARCHIVE_METRIC;
    }

    /**
     * @param ItemMetric $metric
     * @return bool
     */
    public function isValid(ItemMetric $metric)
    {
        return $metric->getType() == $this->getItemMetricType();
    }
}
