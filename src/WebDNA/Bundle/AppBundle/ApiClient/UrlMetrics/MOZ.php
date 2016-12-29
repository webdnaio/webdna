<?php

namespace WebDNA\Bundle\AppBundle\ApiClient\UrlMetrics;

/**
 * Class MOZ
 * @package WebDNA\Bundle\AppBundle\ApiClient\UrlMetrics
 */
class MOZ implements ProviderInterface
{
    /**
     * @var array
     */
    protected $accessKeys;

    /**
     * @var string
     */
    protected $requestUrlTemplate = "http://lsapi.seomoz.com/linkscape/url-metrics/%OBJECT_URL%?Cols=%COLS%&AccessID=%ACCESS_ID%&Expires=%EXPIRES%&Signature=%SIGNATURE%";

    /**
     * @var array
     */
    protected $fields = array(
        'ut' => 'title',
        'uu' => 'canonical_url',
        'ufq' => 'subdomain',
        'upl' => 'root_domain',
        'ueid' => 'external_equity_links',
        'feid' => 'subdomain_external_links',
        'peid' => 'root_domain_external_links',
        'ujid' => 'equity_links',
        'uifq' => 'subdomains_linking',
        'uipl' => 'root domains linking',
        'uid' => 'links',
        'fid' => 'subdomain_subdomains_linking',
        'pid' => 'root_domain_root_domains_linking',
        'umrp' => 'mozrank_url',
        'umrr' => 'mozrank_url_raw',
        'fmrp' => 'mozrank_subdomain',
        'fmrr' => 'mozrank_subdomain_raw',
        'pmrp' => 'mozrank_root_domain',
        'pmrr' => 'mozrank_root_domain_raw',
        'utrp' => 'moztrust',
        'utrr' => 'moztrust_raw',
        'ftrp' => 'moztrust_subdomain',
        'ftrr' => 'moztrust_subdomain_raw',
        'ptrp' => 'moztrust_root_domain',
        'ptrr' => 'moztrust_root_domain_raw',
        'uemrp' => 'mozrank_external_equity',
        'uemrr' => 'mozrank_external_equity_raw',
        'fejp' => 'mozrank_subdomain_external_equity',
        'fejr' => 'mozrank_subdomain_external_equity_raw',
        'pejp' => 'mozrank_root_domain_external_equity',
        'pejr' => 'mozrank_root_domain_external_equity_raw',
        'fjp' => 'mozrank_subdomain_combined',
        'fjr' => 'mozrank_subdomain_combined_raw',
        'pjp' => 'mozrank_root_domain_combined',
        'pjr' => 'mozrank_root_domain_combined_raw',
        'us' => 'http_status_code',
        'fuid' => 'links_to_subdomain',
        'puid' => 'links_to_root_domain',
        'fipl' => 'root_domains_linking_to_subdomain',
        'upa' => 'page_authority',
        'pda' => 'domain_authority',
        'ued' => 'external_links',
        'fed' => 'external_links_to_subdomain',
        'ped' => 'external_links_to_root_domain',
        'pib' => 'linking_c_blocks',
        'ulc' => 'time_last_crawled',
    );

    /**
     * @param $accessId
     * @param $secretKey
     */
    public function __construct($accessKeys)
    {
        $this->accessKeys = $accessKeys;
    }

    /**
     * @param string $url
     * @return mixed|void
     */
    public function getMetrics($url)
    {
        $data = $this->fetchData($url);

        return ($data && $data != '{}')
            ? $this->transform($data)
            : array();
    }

    /**
     * @param $url
     */
    protected function prepareRequestUrl($url)
    {
        list($accessID, $secretKey) = $this->getAccessData();

        $expires = time() + 300;
        $stringToSign = $accessID . "\n" . $expires;
        $binarySignature = hash_hmac('sha1', $stringToSign, $secretKey, true);
        $urlSafeSignature = urlencode(base64_encode($binarySignature));
        $cols = "36028900098197536";

        return str_replace(
            array(
                '%OBJECT_URL%',
                '%COLS%',
                '%ACCESS_ID%',
                '%EXPIRES%',
                '%SIGNATURE%',
            ),
            array(
                urlencode($url),
                $cols,
                $accessID,
                $expires,
                $urlSafeSignature,
            ),
            $this->requestUrlTemplate
        );
    }

    /**
     * @param $url
     * @return mixed
     */
    protected function fetchData($url)
    {
        $ch = curl_init($this->prepareRequestUrl($url));

        curl_setopt_array($ch, array(
            CURLOPT_RETURNTRANSFER => true,
        ));

        $content = curl_exec($ch);

        curl_close($ch);

        return $content;
    }

    /**
     * @param $data
     */
    protected function transform($data)
    {
        $data = json_decode($data, true);
        $result = array();

        foreach ($data as $key => $value) {
            if (isset($this->fields[$key])) {
                $result[$this->fields[$key]] = $value;
            }
        }

        return $result;
    }

    /**
     * @return mixed
     */
    protected function getAccessData()
    {
        return $this->accessKeys[array_rand($this->accessKeys, 1)];
    }
}
