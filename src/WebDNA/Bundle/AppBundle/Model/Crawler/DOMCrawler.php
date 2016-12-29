<?php

namespace WebDNA\Bundle\AppBundle\Model\Crawler;

use WebDNA\Bundle\AppBundle\Model\Crawler\Link\LinkList;

/**
 * Class DOMCrawler
 * @package WebDNA\Bundle\AppBundle\Model\Crawler
 */
class DOMCrawler implements CrawlerInterface
{
    /**
     * @var DOMDocument
     */
    protected $dom;

    /**
     * @var DOMXPath
     */
    protected $xpath;

    /**
     * @var string
     */
    protected $content;

    /**
     * @param $content
     */
    public function __construct($content)
    {
        $this->content = $content;

        $this->prepareDOMObjects((string)$content);
    }

    /**
     * Set dom tree and xpath for input document
     *
     * @param string $html
     *
     */
    protected function prepareDOMObjects($html)
    {
        $this->dom = new \DOMDocument();

        libxml_use_internal_errors(true);

        $this->dom->loadHTML($html);
        $this->xpath = new \DOMXPath($this->dom);
    }

    /**
     * @param $query
     * @return mixed
     */
    public function xpath($query)
    {
        return $this->xpath->query($query);
    }

    /**
     * Check if given document is HTML.
     *
     * @return bool
     */
    public function isHtml()
    {
        $nodes = $this->xpath->query("//html");

        return $nodes->length > 0;
    }

    /**
     * Return document content.
     *
     * @return null|string
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * Return parsed meta title.
     * Title can be defined by <title> or <meta name="title" ...>
     * @return null|string
     */
    public function getMetaTitle()
    {
        $queries = array(
            "//html/head/title",
            "//html/head/meta[@name='title']/@content",
        );
        $result = false;

        foreach ($queries as $query) {
            $node = $this->xpath->query($query);
            $result= $this->getNodeValue($node);

            if ($result) {
                break;
            }
        }

        return $result;
    }

    /**
     * Return parsed meta description.
     * @return null|string
     */
    public function getMetaDescription()
    {
        $node = $this->xpath->query("//html/head/meta[@name='description']/@content");

        return $this->getNodeValue($node);
    }

    /**
     * Return parsed meta keywords.
     *
     * @return null|string
     */
    public function getMetaKeywords()
    {
        $node = $this->xpath->query("//html/head/meta[@name='keywords']/@content");

        return $this->getNodeValue($node);
    }

    /**
     * Return parsed meta charset.
     *
     * @return null|string
     */
    public function getMetaCharset()
    {
        $queries = array(
            "/html/head/meta[@http-equiv='Content-Type']/@content",
            "/html/head/meta/@charset",
        );
        $result = false;

        foreach ($queries as $query) {
            $node = $this->xpath->query($query);
            $result= $this->getNodeValue($node);

            if ($result) {
                break;
            }
        }

        // For non HTML 5 data, get only charset info.
        preg_match('/charset=([a-zA-Z0-9-]+)/', $result, $matches);

        $result = !empty($matches) ? $matches[1] : $result;

        return $result;
    }

    /**
     * Extract plain text content from HTML.
     *
     * @param bool $reduceMultiSpaces
     *
     * @return string
     * @access public
     */
    public function extractPlainText($reduceMultiSpaces = true)
    {
        $removeTags = ['script', 'noscript', 'object', 'style', 'embed', 'noembed', 'head', 'frameset'];
        $dom = clone $this->dom;

        foreach ($removeTags as $tag) {
            $script = $dom->getElementsByTagName($tag);
            $remove = [];

            foreach ($script as $item) {
                $remove[] = $item;
            }

            foreach ($remove as $item) {
                $item->parentNode->removeChild($item);
            }
        }

        $html2text = new \Html2Text\Html2Text($this->content, false, array('width' => 0, 'do_links' => 'none'));
        $text = $html2text->get_text();

        if ($reduceMultiSpaces) {
            $text = $this->reduceMultiSpaces($text);
        }

        return trim($text);
    }

    /**
     * Convert multi white-space characters to one space
     *
     * @param string $text string to convert
     *
     * @return string
     */
    protected function reduceMultiSpaces($text)
    {
        if ($text != '') {
            $text = preg_replace('/\s+/', ' ', $text);
        }

        return $text;
    }

    /**
     * Return instance of LinkList class.
     *
     * @return LinkList
     */
    public function getLinks()
    {
        $nodes = $this->xpath->query("//a");
        $linkList = new LinkList();

        if (count($nodes)) {
            foreach ($nodes as $node) {
                $linkList->push(Link\LinkFactory::createFromNode($this->xpath, $node));
            }
        }

        return $linkList;
    }

    /**
     * Get value from the first node
     *
     * @param DOMNode $node DOM node to process
     *
     * @return bool
     */
    protected function getNodeValue($node)
    {
        $result = false;

        if ($node) {
            if ($node->item(0)) {
                $result = $node->item(0)->nodeValue;
            }
        }

        return $result;
    }
}
