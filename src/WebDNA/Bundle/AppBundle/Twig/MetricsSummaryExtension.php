<?php

namespace WebDNA\Bundle\AppBundle\Twig;

use WebDNA\Bundle\AppBundle\Entity\ItemMetric;

class MetricsSummaryExtension extends \Twig_Extension
{
    public function getFilters()
    {
        return array(
            new \Twig_SimpleFilter('summary_follow', array($this, 'getFollowCounter')),
            new \Twig_SimpleFilter('summary_nofollow', array($this, 'getNofollowCounter')),
            new \Twig_SimpleFilter('summary_malware', array($this, 'getMalwareCounter')),
            new \Twig_SimpleFilter('summary_domains', array($this, 'getDomainsCounter')),
        );
    }

    /**
     * @param array $itemMetricsSummary
     * @return int
     */
    public function getFollowCounter($itemMetricsSummary)
    {
        if (isset($itemMetricsSummary[ItemMetric::TYPE_DOCUMENT_LINKS_ATTR_FOLLOW_METRIC])) {
            return (int) $itemMetricsSummary[ItemMetric::TYPE_DOCUMENT_LINKS_ATTR_FOLLOW_METRIC]['value_numeric_4'];
        } else {
            return 0;
        }
    }

    /**
     * @param array $itemMetricsSummary
     * @return int
     */
    public function getNofollowCounter($itemMetricsSummary)
    {
        if (isset($itemMetricsSummary[ItemMetric::TYPE_DOCUMENT_LINKS_ATTR_FOLLOW_METRIC])) {
            return (int) $itemMetricsSummary[ItemMetric::TYPE_DOCUMENT_LINKS_ATTR_FOLLOW_METRIC]['value_numeric_5'];
        } else {
            return 0;
        }
    }

    public function getLinksFoundCounter($itemMetricsSummary)
    {
        return $this->getFollowCounter($itemMetricsSummary) + $this->getNofollowCounter($itemMetricsSummary);
    }

    /**
     * @param array $itemMetricsSummary
     * @return int
     */
    public function getMalwareCounter($itemMetricsSummary)
    {
        if (isset($itemMetricsSummary[ItemMetric::TYPE_URL_SECURITY_METRIC])) {
            return (int) $itemMetricsSummary[ItemMetric::TYPE_URL_SECURITY_METRIC]['value_numeric_1'];
        } else {
            return 0;
        }
    }

    /**
     * @param array $itemMetricsSummary
     * @return int
     */
    public function getDomainsCounter($itemMetricsSummary)
    {
        if (isset($itemMetricsSummary[ItemMetric::TYPE_DOMAIN_WHOIS_METRIC])) {
            return (int) $itemMetricsSummary[ItemMetric::TYPE_DOMAIN_WHOIS_METRIC]['value_numeric_1'];
        } else {
            return 0;
        }
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'metrics';
    }
}
