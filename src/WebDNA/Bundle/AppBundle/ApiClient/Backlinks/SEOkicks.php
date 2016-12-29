<?php

namespace WebDNA\Bundle\AppBundle\ApiClient\Backlinks;

use Psr\Log\LoggerInterface;

/**
 * Class SEOkicks
 * @package WebDNA\Bundle\AppBundle\ApiClient\Backlinks
 */
class SEOkicks implements ProviderInterface
{
    /**
     * @var
     */
    protected $appId;

    /**
     * @var string
     */
    protected $requestUrlTemplate = 'http://en.seokicks.de/SEOkicksService/V1/inlinkData?appid=%APP_ID%&query=%DOMAIN%&details=2&results=%LIMIT%&output=json';

    /**
     * @var int
     */
    protected $limit;

    /**
     * @var
     */
    protected $logger;

    /**
     * @param int $appId
     * @param int $limit
     * @param LoggerInterface $logger
     */
    public function __construct($appId, $limit, LoggerInterface $logger)
    {
        $this->limit = $limit;
        $this->appId = $appId;
        $this->logger = $logger;
    }

    /**
     * @param string $domain
     * @return string
     */
    public function getLinks($domain)
    {
        $requestUrl = str_replace(
            array(
                '%DOMAIN%',
                '%LIMIT%',
                '%APP_ID%',
            ),
            array(
                $domain,
                $this->limit,
                $this->appId,
            ),
            $this->requestUrlTemplate
        );

        $ch = curl_init($requestUrl);

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        $content = curl_exec($ch);

        try {
            if (!curl_errno($ch)) {
                $content_decoded = json_decode($content);
                if (isset($content_decoded->Results) && is_array($content_decoded->Results)) {
                    foreach ($content_decoded->Results as $item) {
                        yield $item->UrlFrom;
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
