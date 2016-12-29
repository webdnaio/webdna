<?php

namespace WebDNA\Bundle\AppBundle\ApiClient\Backlinks;

use Psr\Log\LoggerInterface;
use Symfony\Component\DomCrawler\Crawler;

/**
 * *** EXPERIMENTAL ***
 *
 * Class OpenLinkProfiler
 * @package WebDNA\Bundle\AppBundle\ApiClient\Backlinks
 */
class OpenLinkProfiler implements ProviderInterface
{
    /**
     * @var
     */
    protected $accessId;

    /**
     * @var
     */
    protected $secretKey;

    /**
     * @var
     */
    protected $accessKeys;

    /**
     * @var string
     */
    protected $requestUrlTemplate = 'http://www.openlinkprofiler.org/r/%DOMAIN%?page=%PAGE_NUMBER%';

    /**
     * @var int
     */
    protected $pages;

    /**
     * @var
     */
    protected $logger;

    /**
     * @param int $pages
     * @param LoggerInterface $logger
     */
    public function __construct($pages, LoggerInterface $logger = null)
    {
        $this->pages = $pages;
        $this->logger = $logger;
    }

    /**
     * @param string $domain
     * @return string
     */
    public function getLinks($domain)
    {
        for ($pageNumber = 1; $pageNumber <= $this->pages; $pageNumber++) {
            $requestUrl = str_replace(
                array(
                    '%DOMAIN%',
                    '%PAGE_NUMBER%'
                ),
                array(
                    $domain,
                    $pageNumber
                ),
                $this->requestUrlTemplate
            );

            $ch = curl_init($requestUrl);

            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt(
                $ch,
                CURLOPT_USERAGENT,
                'Mozilla/5.0 (Windows; U; Windows NT 5.1;'
                . ' en-US) AppleWebKit/525.19 (KHTML, like Gecko) Chrome/1.0.154.53 Safari/525.19'
            );

            $content = curl_exec($ch);

            try {
                if (!curl_errno($ch)) {
                    $crawler = new Crawler($content);
                    $crawler = $crawler->filterXPath("//table[contains(@class, 'linktext')]/tbody/tr/td[2]/a/@href");

                    if ($crawler instanceof \Traversable) {
                        foreach ($crawler as $domElement) {
                            $parsed_url = parse_url($domElement->value);
                            if (isset($parsed_url['scheme']) && $parsed_url['host'] != $domain) {
                                yield $domElement->value;
                            }
                        }
                    } else {
                        throw new \Exception('There are no urls for ' . $domain);
                    }
                } else {
                    throw new \Exception('Problem with fetching urls for ' . $domain);
                }
            } catch (\Exception $e) {
                $this->logger->alert(__CLASS__ . ' - ' . $e->getMessage() . PHP_EOL);
            } finally {
                curl_close($ch);
            }
        }
    }
}
