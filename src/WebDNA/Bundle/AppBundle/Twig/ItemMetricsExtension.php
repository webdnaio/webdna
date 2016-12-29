<?php

namespace WebDNA\Bundle\AppBundle\Twig;

use WebDNA\Bundle\AppBundle\Entity\ItemMetric;

class ItemMetricsExtension extends \Twig_Extension
{

    /**
     * Supported metric types to extract with associated method name
     * @var array
     */
    protected $metricMethods = [
        ItemMetric::TYPE_DOCUMENT_LINKS_ATTR_FOLLOW_METRIC => 'documentLinksAttrFollow',
        ItemMetric::TYPE_URL_SECURITY_METRIC => 'urlSecurity',
        ItemMetric::TYPE_DOMAIN_WHOIS_METRIC => 'domainWhois',
        ItemMetric::TYPE_DOMAIN_MOZ_METRIC => 'domainMoz',
        ItemMetric::TYPE_URL_INTERNET_ARCHIVE_METRIC => 'internetArchive',
        ItemMetric::TYPE_DOCUMENT_HTML_TEXT_STRUCTURE_METRIC => 'documentHtmlTextStructure',
        ItemMetric::TYPE_DOCUMENT_HTML_META_METRIC => 'documentHtmlMeta',
        ItemMetric::TYPE_PAGE_LINKS_DIRECTIONS_METRIC => 'pageLinksDirections',
        ItemMetric::TYPE_DOCUMENT_LINKS_DISTRIBUTION_METRIC => 'documentLinksDistribution',
        ItemMetric::TYPE_DOCUMENT_WEBSITE_LINKS_DISTRIBUTION_METRIC => 'documentWebsiteLinksDistribution',
        ItemMetric::TYPE_DOCUMENT_EXTERNAL_LINKS_DISTRIBUTION_METRIC => 'documentExternalLinksDistribution',
        ItemMetric::TYPE_URL_PERFORMANCE_METRIC => 'urlPerformance',
    ];

    /**
     * Metrics fields description
     * @var array
     */
    protected $metricFieldLabels = [

            ItemMetric::TYPE_DOCUMENT_LINKS_ATTR_FOLLOW_METRIC => [
                'value_numeric_1' => 'Total links count',
                'value_numeric_2' => 'Follow links count',
                'value_numeric_3' => 'Nofollow links count',
                'value_numeric_4' => 'Website follow links count',
                'value_numeric_5' => 'Website nofollow links count',
            ],
            ItemMetric::TYPE_URL_SECURITY_METRIC => [
                'value_numeric_1' => 'Malware check',
            ],
            ItemMetric::TYPE_DOMAIN_WHOIS_METRIC => [],
            ItemMetric::TYPE_DOMAIN_MOZ_METRIC => [
                'value_numeric_1' => 'Moz Domain Authority',
                'value_numeric_2' => 'Moz Rank',
            ],
            ItemMetric::TYPE_URL_INTERNET_ARCHIVE_METRIC => [
                'value_numeric_1' => 'Discovery date',
            ],
            ItemMetric::TYPE_DOCUMENT_HTML_TEXT_STRUCTURE_METRIC => [
                'value_numeric_1' => 'Characters overall',
                'value_numeric_2' => 'Only text characters',
                'value_numeric_3' => 'HTML2Text ratio',
            ],
            ItemMetric::TYPE_DOCUMENT_HTML_META_METRIC => [
                'value_numeric_1' => 'Meta title length',
                'value_numeric_2' => 'Meta description length',
                'value_numeric_3' => 'Meta keywords length',
            ],
            ItemMetric::TYPE_PAGE_LINKS_DIRECTIONS_METRIC => [],
            ItemMetric::TYPE_DOCUMENT_LINKS_DISTRIBUTION_METRIC => [],
            ItemMetric::TYPE_DOCUMENT_WEBSITE_LINKS_DISTRIBUTION_METRIC => [],
            ItemMetric::TYPE_DOCUMENT_EXTERNAL_LINKS_DISTRIBUTION_METRIC => [],
            ItemMetric::TYPE_URL_PERFORMANCE_METRIC => [
                'value_numeric_1' => 'Load time',
            ],
    ];

    public function __construct()
    {
    }

    /**
     * @return array
     */
    public function getFunctions()
    {
        return array(
            new \Twig_SimpleFunction('metrics', array($this, 'getMetrics')),
            new \Twig_SimpleFunction('item_metrics_data', array($this, 'getMetricsData')),
            new \Twig_SimpleFunction('item_metric_label', array($this, 'getFieldLabel')),
        );
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'item_metrics_extension';
    }

    /**
     * @param array $itemMetrics
     * @param array $metrics
     * @param array|null $customMethods
     * @return array
     */
    public function getMetrics(array $itemMetrics, array $metrics, $customMethods = null)
    {
        $processedMetrics = [];
        $metricMethods = [];

        foreach ($metrics as $metric) {
            $metricMethods[$metric] = $this->metricMethods[$metric];
        }

        foreach ($itemMetrics as $itemMetric) {
            if (isset($metricMethods[$itemMetric['type']])) {
                // custom methods support
                if (isset($customMethods[$itemMetric['type']])) {
                    $processedMetrics[$metricMethods[$itemMetric['type']]] =

                        $this->{$customMethods[$itemMetric['type']]['method']}($itemMetric,
                            $customMethods[$itemMetric['type']]['params']
                        );

                    continue;
                }

                $methodName = $metricMethods[$itemMetric['type']];
                $methodParams = null;

                if (method_exists($this, $methodName)) {
                    $processedMetrics[$methodName] = $this->{$methodName}($itemMetric);
                }
            }
        }

        return $processedMetrics;
    }

    /**
     * Return metric label/description to show in i.e. dashboard or api
     * @param int $metric
     * @param string $field
     * @return string
     */
    public function getFieldLabel($metric, $field)
    {
        return $this->metricFieldLabels[$metric][$field];
    }

    /**
     * @param array $metrics
     * @return array
     */
    public function getmetricFieldLabels(array $metrics)
    {
        $descriptions = [];

        foreach ($metrics as $metric) {
            $descriptions[$metric] = $this->metricFieldLabels[$metric];
        }

        return $descriptions;
    }

    /**
     * @param array $itemMetrics
     * @param array|null $metrics
     * @return array
     */
    public function getMetricsData(
        array $itemMetrics,
        array $metrics = [
            ItemMetric::TYPE_DOCUMENT_LINKS_ATTR_FOLLOW_METRIC,
            ItemMetric::TYPE_URL_SECURITY_METRIC
        ]
    ) {
        return $this->getMetrics(
            $itemMetrics,
            $metrics,
            [
                ItemMetric::TYPE_DOCUMENT_LINKS_ATTR_FOLLOW_METRIC
                => ['method' => 'documentLinksAttrFollowCustom', 'params' => null],
            ]
        );
    }

    /**
     * @param array $itemMetric
     * @return array|null
     */
    public function documentLinksAttrFollow(array $itemMetric)
    {
        return [
            $this->metricFieldLabels[ItemMetric::TYPE_DOCUMENT_LINKS_ATTR_FOLLOW_METRIC]['value_numeric_1']
            => (int) $itemMetric['value_numeric_1'],
            $this->metricFieldLabels[ItemMetric::TYPE_DOCUMENT_LINKS_ATTR_FOLLOW_METRIC]['value_numeric_2']
            => (int) $itemMetric['value_numeric_2'],
            $this->metricFieldLabels[ItemMetric::TYPE_DOCUMENT_LINKS_ATTR_FOLLOW_METRIC]['value_numeric_3']
            => (int) $itemMetric['value_numeric_3'],
            $this->metricFieldLabels[ItemMetric::TYPE_DOCUMENT_LINKS_ATTR_FOLLOW_METRIC]['value_numeric_4']
            => (int) $itemMetric['value_numeric_4'],
            $this->metricFieldLabels[ItemMetric::TYPE_DOCUMENT_LINKS_ATTR_FOLLOW_METRIC]['value_numeric_5']
            => (int) $itemMetric['value_numeric_5'],
        ];
    }

    /**
     * @param array $itemMetric
     * @return array|null
     */
    public function documentLinksAttrFollowCustom(array $itemMetric)
    {
        $returnValues = [
            'links_count' => 0,
            'nofollow_count' => 0,
        ];

        $linksToYou = $itemMetric['value_numeric_4'] + $itemMetric['value_numeric_5'];

        if ($linksToYou) {
            $returnValues['links_count'] = number_format($linksToYou, 0, '.', ' ');

            if ($itemMetric['value_numeric_5'] > 0) {
                $returnValues['nofollow_count'] = number_format($itemMetric['value_numeric_5'], 0, '.', ' ');
            }
        }

        return $returnValues;
    }

    /**
     * @param array $itemMetric
     * @return string
     */
    public function urlSecurity(array $itemMetric)
    {
        if ($itemMetric['value_numeric_1'] == 1) {
            return 'Yes';
        } else {
            return 'No';
        }
    }

    /**
     * @param array $itemMetric
     * @return array|null
     */
    public function domainWhois(array $itemMetric)
    {
        return [
            $this->metricFieldLabels[ItemMetric::TYPE_DOMAIN_WHOIS_METRIC]['value_numeric_1']
            => $itemMetric['value_numeric_1'],
            $this->metricFieldLabels[ItemMetric::TYPE_DOMAIN_WHOIS_METRIC]['value_numeric_2']
            => $itemMetric['value_numeric_2'],
            $this->metricFieldLabels[ItemMetric::TYPE_DOMAIN_WHOIS_METRIC]['value_numeric_3']
            => $itemMetric['value_numeric_3'],
        ];
    }

    /**
     * @param array $itemMetric
     * @return array|null
     */
    public function domainMoz(array $itemMetric)
    {
        return [
            $this->metricFieldLabels[ItemMetric::TYPE_DOMAIN_MOZ_METRIC]['value_numeric_1']
            => intval($itemMetric['value_numeric_1']),
            $this->metricFieldLabels[ItemMetric::TYPE_DOMAIN_MOZ_METRIC]['value_numeric_2']
            => intval($itemMetric['value_numeric_2']),
        ];
    }

    /**
     * @param array $itemMetric
     * @return array|null
     */
    public function internetArchive(array $itemMetric)
    {
        if ($itemMetric['value_numeric_1']==null) {
            return null;
        } else {
            $date = new \DateTime();
            $date->setTimestamp(intval($itemMetric['value_numeric_1']));
            return [
                $this->metricFieldLabels[ItemMetric::TYPE_URL_INTERNET_ARCHIVE_METRIC]['value_numeric_1']
                => $date
            ];
        }
    }

    /**
     * @param array $itemMetric
     * @return array|null
     */
    public function documentHtmlTextStructure(array $itemMetric)
    {
        return [
            $this->metricFieldLabels[ItemMetric::TYPE_DOCUMENT_HTML_TEXT_STRUCTURE_METRIC]['value_numeric_1']
                => (int) $itemMetric['value_numeric_1'],
            $this->metricFieldLabels[ItemMetric::TYPE_DOCUMENT_HTML_TEXT_STRUCTURE_METRIC]['value_numeric_2']
                => (int) $itemMetric['value_numeric_2'],
            $this->metricFieldLabels[ItemMetric::TYPE_DOCUMENT_HTML_TEXT_STRUCTURE_METRIC]['value_numeric_3']
                => number_format($itemMetric['value_numeric_3'], 2, '.', ''),
        ];
    }

    /**
     * @param array $itemMetric
     * @return array|null
     */
    public function documentHtmlMeta(array $itemMetric)
    {
        return [
            $this->metricFieldLabels[ItemMetric::TYPE_DOCUMENT_HTML_META_METRIC]['value_numeric_1']
            => $itemMetric['value_numeric_1'],
            $this->metricFieldLabels[ItemMetric::TYPE_DOCUMENT_HTML_META_METRIC]['value_numeric_2']
            => $itemMetric['value_numeric_2'],
            $this->metricFieldLabels[ItemMetric::TYPE_DOCUMENT_HTML_META_METRIC]['value_numeric_2']
            => $itemMetric['value_numeric_3'],
        ];
    }

    /**
     * @param array $itemMetric
     * @return array|null
     */
    public function documentLinksDistribution(array $itemMetric)
    {
        return [
            $this->metricFieldLabels[ItemMetric::TYPE_DOCUMENT_LINKS_DISTRIBUTION_METRIC]['value_numeric_1']
            => $itemMetric['value_numeric_1'],
            $this->metricFieldLabels[ItemMetric::TYPE_DOCUMENT_LINKS_DISTRIBUTION_METRIC]['value_numeric_2']
            => $itemMetric['value_numeric_2'],
            $this->metricFieldLabels[ItemMetric::TYPE_DOCUMENT_LINKS_DISTRIBUTION_METRIC]['value_numeric_3']
            => $itemMetric['value_numeric_3'],
            $this->metricFieldLabels[ItemMetric::TYPE_DOCUMENT_LINKS_DISTRIBUTION_METRIC]['value_numeric_4']
            => $itemMetric['value_numeric_4'],
            $this->metricFieldLabels[ItemMetric::TYPE_DOCUMENT_LINKS_DISTRIBUTION_METRIC]['value_numeric_5']
            => $itemMetric['value_numeric_5'],
        ];
    }

    /**
     * @param array $itemMetric
     * @return string|null
     */
    public function documentWebsiteLinksDistribution(array $itemMetric)
    {
        if ($itemMetric['value_numeric_1'] == 1) {
            return 'VERY HIGH';
        }

        if ($itemMetric['value_numeric_1'] == 2) {
            return 'HIGH';
        }

        if ($itemMetric['value_numeric_1'] == 3) {
            return 'MEDIUM';
        }

        if ($itemMetric['value_numeric_1'] == 4) {
            return 'LOW';
        }

        if ($itemMetric['value_numeric_1'] == 5) {
            return 'VERY LOW';
        }

        if ($itemMetric['value_numeric_1'] === null) {
            return 'NOT FOUND';
        }
    }

    /**
     * @param array $itemMetric
     * @return string|null
     */
    public function documentExternalLinksDistribution(array $itemMetric)
    {
        return [
            $this->metricFieldLabels[ItemMetric::TYPE_DOCUMENT_EXTERNAL_LINKS_DISTRIBUTION_METRIC]['value_numeric_1']
            => $itemMetric['value_numeric_1'],
            $this->metricFieldLabels[ItemMetric::TYPE_DOCUMENT_EXTERNAL_LINKS_DISTRIBUTION_METRIC]['value_numeric_2']
            => $itemMetric['value_numeric_2'],
            $this->metricFieldLabels[ItemMetric::TYPE_DOCUMENT_EXTERNAL_LINKS_DISTRIBUTION_METRIC]['value_numeric_3']
            => $itemMetric['value_numeric_3'],
            $this->metricFieldLabels[ItemMetric::TYPE_DOCUMENT_EXTERNAL_LINKS_DISTRIBUTION_METRIC]['value_numeric_4']
            => $itemMetric['value_numeric_4'],
            $this->metricFieldLabels[ItemMetric::TYPE_DOCUMENT_EXTERNAL_LINKS_DISTRIBUTION_METRIC]['value_numeric_5']
            => $itemMetric['value_numeric_5'],
        ];
    }

    /**
     * @param array $itemMetric
     * @return array|null
     */
    public function urlPerformance(array $itemMetric)
    {
        return [
            $this->metricFieldLabels[ItemMetric::TYPE_URL_PERFORMANCE_METRIC]['value_numeric_1']
                => number_format($itemMetric['value_numeric_1'], 2, '.', '') . 's'
        ];
    }
}
