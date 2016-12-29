<?php

namespace  WebDNA\Bundle\AppBundle\ApiClient\Rserve;

use WebDNA\Bundle\AppBundle\Entity\ItemMetric;
use WebDNA\Bundle\AppBundle\Entity\Page;

/**
 * Class Sybilla
 * @package WebDNA\Bundle\AppBundle\ApiClient\Rserve
 */
class Sybilla extends Client
{
    /**
     * Classify page with Sybilla prediction.
     *
     * This method is a wrapper for RServe communication flow.
     *
     * @param Page $page,
     * @param array of ItemMetric
     * @return int
     * @access public
     */
    public function classify(Page $page, $metrics)
    {
        $name = sprintf(
            'data_%d_%s',
            time(),
            bin2hex(openssl_random_pseudo_bytes(16))
        );

        $data = $this->prepareMetrics($page, $metrics);

        $this->assign('String', $name, array_values($data), array_keys($data));

        $result = $this->evalString('webdnaSybilla::classify_metrics(' . $name . ', ' . $name . self::COLUMN_LABELS_SUFFIX . ')');

        $this->evalString('rm(' . $name . ', ' . $name . self::COLUMN_LABELS_SUFFIX . ')');

        return $result;
    }

    /**
     * Helper function. Converts array of ItemMetrics into flat array of values
     * with Sybilla names.
     *
     * Convertion beteween datatypes (eg. factors, characters, numerics) is part
     * of Sybilla flow.
     *
     * @param Page $page
     * @param array $metrics
     * @return array
     * @access protected
     */
    protected function prepareMetrics(Page $page, $metrics)
    {
        $data = array();

        foreach ($metrics as $metric) {
            if (isset(ItemMetric::$FIELDS[$metric->getType()])) {
                $fields = ItemMetric::$FIELDS[$metric->getType()];

                foreach ($fields as $propertyName => $modelName) {
                    if ($modelName) {
                        // Convert method to camel-case notation, based on field definition.
                        $method = ucwords(str_replace('_', ' ', $propertyName));
                        $method = 'get' . str_replace(' ', '', $method);

                        // Execute computed method and store the result.
                        $data[$modelName] = $metric->{$method}();
                    }
                }
            } else {
                // exception
            }
        }

        $data['http_code'] = $page->getHttpCode();

        return $data;
    }
}
