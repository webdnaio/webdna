<?php

namespace WebDNA\Bundle\AppBundle\ApiClient\WebArchive;

/**
 * Class Snapshot
 * @package WebDNA\Bundle\AppBundle\ApiClient\WebArchive
 */
class Snapshot
{
    /**
     * @var
     */
    protected $available;
    protected $url;
    protected $date;
    protected $status;

    /**
     * @param $url
     * @param \DateTime $date
     * @param $available
     * @param $status
     */
    public function __construct($url, \DateTime $date, $available, $status)
    {
        $this->url = $url;
        $this->date = $date;
        $this->available = $available;
        $this->status = $status;
    }

    /**
     * @return mixed
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * @return mixed
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * @return mixed
     */
    public function isAvailable()
    {
        return $this->isAvailable();
    }

    /**
     * @return mixed
     */
    public function getstatus()
    {
        return $this->status;
    }
}
