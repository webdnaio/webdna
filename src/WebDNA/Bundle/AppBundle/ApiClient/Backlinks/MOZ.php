<?php

namespace WebDNA\Bundle\AppBundle\ApiClient\Backlinks;

use Psr\Log\LoggerInterface;
use WebDriver\Exception;

/**
 * Class MOZ
 * @package WebDNA\Bundle\AppBundle\ApiClient\Backlinks
 */
class MOZ implements ProviderInterface
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
    protected $requestUrlTemplate = 'http://lsapi.seomoz.com/linkscape/links/%DOMAIN%?Scope=domain_to_page&Limit=%LIMIT%&Sort=domains_linking_domain&AccessID=%ACCESS_ID%&Expires=%EXPIRES%&Signature=%SIGNATURE%';

    /**
     * @var int
     */
    protected $limit;

    protected $logger;

    /**
     * @param array $accessKeys
     * @param int $limit
     * @param LoggerInterface $logger
     *
     */
    public function __construct($accessKeys, $limit, LoggerInterface $logger)
    {
        $this->accessKeys = $accessKeys;
        $this->limit = $limit;
        $this->logger = $logger;
        list($this->accessId, $this->secretKey) = $this->getAccessData();
    }

    /**
     * @param string $domain
     * @return string
     */
    public function getLinks($domain)
    {
        $expires = time() + 300;
        $requestUrl = str_replace(
            array(
                '%DOMAIN%',
                '%LIMIT%',
                '%ACCESS_ID%',
                '%EXPIRES%',
                '%SIGNATURE%',
            ),
            array(
                $domain,
                $this->limit,
                $this->accessId,
                $expires,
                $this->generateSignature($expires),
            ),
            $this->requestUrlTemplate
        );

        $ch = curl_init($requestUrl);

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        $content = curl_exec($ch);

        try {
            if (!curl_errno($ch)) {
                $decoded = json_decode($content);
                if (is_array($decoded)) {
                    foreach ($decoded as $item) {
                        yield 'http://' . $item->uu;
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

    /**
     * @param $expires
     * @return string
     */
    protected function generateSignature($expires)
    {
        $stringToSign = $this->accessId . "\n" . $expires;

        return urlencode(base64_encode(hash_hmac('sha1', $stringToSign, $this->secretKey, true)));
    }


    /**
     * @return mixed
     */
    protected function getAccessData()
    {
        return $this->accessKeys[array_rand($this->accessKeys, 1)];
    }
}
