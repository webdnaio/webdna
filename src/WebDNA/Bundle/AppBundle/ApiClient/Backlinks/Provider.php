<?php

namespace WebDNA\Bundle\AppBundle\ApiClient\Backlinks;

/**
 * Class Provider
 * @package WebDNA\Bundle\AppBundle\ApiClient\Backlinks
 */
class Provider
{
    /**
     * @var
     */
    protected $api;

    /**
     * @param ProviderInterface $api
     */
    public function __construct(ProviderInterface $api)
    {
        $this->api = $api;
    }

    /**
     * @param string $domain
     *
     * @return array
     */
    public function getLinks($domain)
    {
        return $this->api->getLinks($domain);
    }
}
