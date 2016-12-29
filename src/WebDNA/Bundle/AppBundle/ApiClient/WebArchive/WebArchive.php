<?php

namespace WebDNA\Bundle\AppBundle\ApiClient\WebArchive;

use Buzz\Client\ClientInterface;
use Buzz\Message\Request;
use Buzz\Message\Response;

/**
 * Class WebArchive
 *
 * http://archive.org/help/wayback_api.php
 *
 * @package WebDNA\Bundle\AppBundle\ApiClient\WebArchive
 */
class WebArchive
{
    /**
     * @var \Buzz\Client\ClientInterface
     */
    protected $client;

    /**
     * @param ClientInterface $client
     */
    public function __construct(ClientInterface $client)
    {
        $this->client = $client;
    }

    /**
     * @param $url
     * @param null $date
     * @return array
     */
    public function getSnapshots($url, $date = null)
    {
        $request = new Request('GET', '/wayback/available?url=' . urlencode($url) . '&timestamp=', 'http://archive.org');
        $response = new Response();
        $snapshots = array();

        $this->client->send($request, $response);

        if ($response->isSuccessful()) {
            $data = json_decode($response->getContent());

            if ($data && isset($data->archived_snapshots)) {
                if (isset($data->archived_snapshots->closest)) {
                    $item = $data->archived_snapshots->closest;

                    $snapshots[] = new Snapshot(
                        $item->url,
                        \DateTime::createFromFormat('YmdHis', $item->timestamp),
                        $item->available,
                        $item->status
                    );
                }
            }
        }

        return $snapshots;
    }
}
