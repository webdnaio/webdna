<?php

namespace WebDNA\Bundle\AppBundle\ApiClient\UrlMetrics;

/**
 * Interface ProviderInterface
 * @package WebDNA\Bundle\AppBundle\ApiClient\UrlMetrics
 */
interface ProviderInterface
{
    /**
     * @param string $url
     * @return mixed
     */
    public function getMetrics($url);
}
