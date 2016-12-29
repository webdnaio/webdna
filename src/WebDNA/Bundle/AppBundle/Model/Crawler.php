<?php

namespace WebDNA\Bundle\AppBundle\Model;

use WebDNA\Bundle\AppBundle\Model\Crawler\Link\LinkFactory;
use WebDNA\Bundle\AppBundle\Model\Crawler\Link\LinkList;
use WebDNA\Bundle\AppBundle\Model\Crawler\Link\TextLink;

/**
 * Class Crawler
 */
class Crawler extends \Symfony\Component\DomCrawler\Crawler
{
    /**
     * Constructor.
     *
     * @param mixed  $node A Node to use as the base for the crawling
     * @param string $uri  The current URI or the base href value
     *
     * @api
     */
    public function __construct($node = null, $uri = null)
    {
        $this->uri = $uri;

        // @todo: Add support for detecting charset
        $this->add($node);
    }

    /**
     * Check if given document is HTML.
     *
     * @todo Probably we should move this logic to separated layer of content recognizers,
     * for detecing other contents like images, javascrips, rss, etc.
     *
     * @return bool
     */
    public function isHtml()
    {
        $nodes = $this->filterXPath('//html/body');

        return count($nodes) > 0 ?: null;
    }

    /**
     * Return parsed meta title.
     * Title can be defined by <title> or <meta name
     *
     * @param void
     * @return null|string
     */
    public function getMetaTitle()
    {
        $nodes = $this->filterXPath('//html/head/title');
        $value = null;

        if (count($nodes)) {
            $value = $nodes->text();
        } else {
            $nodes = $this->filterXPath('//html/head/meta[@name="title"]');

            $value = count($nodes) ? $nodes->attr('content') : null;
        }

        return $value;
    }

    /**
     * Return parsed meta description.
     *
     * @param void
     * @return null|string
     */
    public function getMetaDescription()
    {
        $nodes = $this->filterXPath('//meta[@name="description"]');

        return count($nodes) ? $nodes->attr('content') : null;
    }

    /**
     * Return parsed meta keywords.
     *
     * @param void
     * @return null|string
     */
    public function getMetaKeywords()
    {
        $nodes = $this->filterXPath('//meta[@name="keywords"]');

        return count($nodes) ? $nodes->attr('content') : null;
    }

    /**
     * Return parsed meta charset.
     *
     * @param void
     * @return null|string
     */
    public function getMetaCharset()
    {
        $value = null;

        // Check all meta tags and search for charset definition.
        foreach ($this->filterXPath('//meta') as $meta) {
            $matches = array();

            preg_match(
                '/\<meta[^\>]+charset *= *["\']?([a-zA-Z\-0-9]+)/i',
                $meta->ownerDocument->saveHTML($meta),
                $matches
            );

            if (!empty($matches)) {
                $value = $matches[1];

                break;
            }
        }

        return $value;
    }

    /**
     * Extract plain text content from HTML.
     *
     * @param bool
     * @return string
     * @access public
     */
    public function extractPlainText($reduceMultiSpaces = true)
    {
        $text = preg_replace(
            array(
                '@<head[^>]*?>.*?</head>@siu',
                '@<style[^>]*?>.*?</style>@siu',
                '@<script[^>]*?.*?</script>@siu',
                '@<object[^>]*?.*?</object>@siu',
                '@<embed[^>]*?.*?</embed>@siu',
                '@<applet[^>]*?.*?</applet>@siu',
                '@<noframes[^>]*?.*?</noframes>@siu',
                '@<noscript[^>]*?.*?</noscript>@siu',
                '@<noembed[^>]*?.*?</noembed>@siu',
                '@</?((address)|(blockquote)|(center)|(del))@iu',
                '@</?((div)|(h[1-9])|(ins)|(isindex)|(p)|(pre))@iu',
                '@</?((dir)|(dl)|(dt)|(dd)|(li)|(menu)|(ol)|(ul))@iu',
                '@</?((table)|(th)|(td)|(caption))@iu',
                '@</?((form)|(button)|(fieldset)|(legend)|(input))@iu',
                '@</?((label)|(select)|(optgroup)|(option)|(textarea))@iu',
                '@</?((frameset)|(frame)|(iframe))@iu',
            ),
            array(
                ' ', ' ', ' ', ' ', ' ', ' ', ' ', ' ', ' ',
                "\n\$0", "\n\$0", "\n\$0", "\n\$0", "\n\$0", "\n\$0", "\n\$0", "\n\$0",
            ),
            $this->html()
        );
        $text = strip_tags($text);
        if ($reduceMultiSpaces) {
            $text = $this->reduceMultiSpaces($text);
        }
        $text = trim($text);

        return $text;
    }

    /**
     * @return LinkList
     */
    public function getLinks()
    {
        $linkList = new LinkList();

        $this->filter('a')->each(function (Crawler $node) use ($linkList) {
            $link = LinkFactory::createFromNode($node);

            $linkList->push($link);
        });

        return $linkList;
    }

    /**
     * Return plain text
     *
     * @param bool
     * @return string
     */
    public function getPlainText($reduceMultiSpaces = true)
    {
        return ($reduceMultiSpaces)
            ? $this->reduceMultiSpaces($this->text()) : $this->text();
    }


    /**
     * Return scripts content
     *
     * @param bool
     * @return array
     */
    public function getScriptsPlainText($multiWhiteSpaceCharacters = false)
    {
        $scriptsContent = array();
        foreach ($this->filterXPath('//script') as $script) {
            $scriptsContent[] = (!$multiWhiteSpaceCharacters)
                ? $this->reduceMultiSpaces($script->nodeValue) : $script->nodeValue;
        }
        return $scriptsContent;
    }

    /**
     * Return anchors
     *
     * @param void
     * @return array
     */
    public function getAnchors()
    {
        return $this->filter('a');
    }


    /**
     * Return body html
     *
     * @param void
     * @return array
     */
    public function getBody()
    {
        return $this->filter('body');
    }

    /**
     * Convert multi white-space characters to one space
     *
     * @param string to convert
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
     * Return value of attribute
     *
     * @param Crawler
     * @param string $selector selector to search
     * @param string $attr attribute to search
     * @return null|string
     */
    protected function getSelectorAttrValue(Crawler $node, $selector, $attr)
    {
        $node = $node->filter($selector);
        return ($node->count()) ? $node->attr($attr) : null;
    }
}
