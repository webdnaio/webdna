<?php

namespace WebDNA\Bundle\AppBundle\Chain;

use Psr\Log\LoggerInterface;
use WebDNA\Bundle\AppBundle\ApiClient\Backlinks\Provider;
use WebDNA\Bundle\AppBundle\ApiClient\Backlinks\ProviderInterface;
use WebDNA\Bundle\AppBundle\Chain;

final class BacklinksChain
{

    /**
     * @var Provider[]
     */
    private $providers = [];

    /**
     * @var
     */
    private $logger;

    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    /**
     * @param ProviderInterface $provider
     * @param string $alias
     */
    public function addProvider(ProviderInterface $provider, $alias)
    {
        $this->providers[$alias] = $provider;
    }

    /**
     * @param string $domain
     * @param string $alias
     */
    public function runByAlias($domain, $alias)
    {
        $this->providers[$alias]->getLinks($domain, $alias);
    }

    /**
     * @param string $domain
     * @return array
     */
    public function runAll($domain)
    {
        $links = [];
        $counters = [];

        foreach ($this->providers as $alias => $provider) {
            $counters[$alias] = 0;
            foreach ($provider->getLinks($domain) as $url) {
                if (!in_array($url, $links)) {
                    $links[] = $url;
                    $counters[$alias]++;
                }
            }
        }

        $this->logger->info($domain . ' backlinks providers stats: ' . var_export($counters, true));

        return $links;
    }
}
