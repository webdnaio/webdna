<?php

namespace WebDNA\Bundle\AppBundle\Model\Crawler;

/**
 * Interface CrawlerInterface
 * @package WebDNA\Bundle\AppBundle\Model\Crawler
 */
interface CrawlerInterface
{
    /**
     * Check if given document is HTML.
     *
     * @return bool
     */
    public function isHtml();

    /**
     * Return document content.
     *
     * @return null|string
     */
    public function getContent();

    /**
     * Return parsed meta title.
     * Title can be defined by <title> or <meta name="title" ...>
     *
     * @param void
     * @return null|string
     */
    public function getMetaTitle();

    /**
     * Return parsed meta description.
     *
     * @param void
     * @return null|string
     */
    public function getMetaDescription();

    /**
     * Return parsed meta keywords.
     *
     * @param void
     * @return null|string
     */
    public function getMetaKeywords();

    /**
     * Return parsed meta charset.
     *
     * @param void
     * @return null|string
     */
    public function getMetaCharset();

    /**
     * Extract plain text content from HTML.
     *
     * @param bool
     * @return string
     * @access public
     */
    public function extractPlainText($reduceMultiSpaces = true);

    /**
     * Return instance of LinkList class.
     *
     * @return LinkList
     */
    public function getLinks();
}
