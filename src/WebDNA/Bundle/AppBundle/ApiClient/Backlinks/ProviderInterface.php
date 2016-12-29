<?php

namespace WebDNA\Bundle\AppBundle\ApiClient\Backlinks;

/**
 * Interface ProviderInterface
 * @package WebDNA\Bundle\AppBundle\ApiClient\Backlinks
 */
interface ProviderInterface
{
    /**
     * @param string $domain
     * @return mixed
     */
    public function getLinks($domain);
}
