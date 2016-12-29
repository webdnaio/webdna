<?php

namespace WebDNA\Bundle\AppBundle\Model\Crawler\Link;

/**
 * Class Link
 *
 * @todo Add support for links like: #, javascript:
 *
 * @package WebDNA\Bundle\AppBundle\Model\Crawler\Link
 */
abstract class Link
{
    protected $uri;
    protected $text;
    protected $isFollow;

    public function __construct()
    {
    }

    /**
     * @return string|null
     */
    public function getUri()
    {
        return $this->uri;
    }

    /**
     * @param string $uri uri
     */
    public function setUri($uri)
    {
        $this->uri = $uri;
    }

    /**
     * @return mixed
     */
    public function getDomain()
    {
        return parse_url(
            mb_strtolower($this->getUri(), 'UTF-8'),
            PHP_URL_HOST
        );
    }

    /**
     * @return string|null
     */
    public function getText()
    {
        return $this->text;
    }

    /**
     * @param string $text anchor text
     */
    public function setText($text)
    {
        $this->text = $text;
    }

    /**
     * @param boolean $isFollow type of link, if attribute nofollow exists then it's false
     * @return bool
     */
    public function setFollow($isFollow)
    {
        $this->isFollow = $isFollow;
    }

    /**
     * @return bool
     */
    public function isFollow()
    {
        return $this->isFollow;
    }

    /**
     * @return bool
     */
    public function isRelative()
    {
        return !$this->isAbsolute();
    }

    /**
     * @return bool
     */
    public function isAbsolute()
    {
        $isAbsolute = false;
        $parts = parse_url($this->getUri());

        if (is_array($parts)) {
            $isAbsolute = !empty($parts['host']);
        }

        return $isAbsolute;
    }

    /**
     * @return int
     */
    abstract public function getType();

    /**
     * @return string
     */
    abstract public function getTypeLabel();
}
