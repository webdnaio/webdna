<?php

namespace WebDNA\Bundle\AppBundle\Model\Crawler\Link;

use WebDNA\Bundle\AppBundle\Model\Crawler\Link;

/**
 * Class LinkFactory
 * @package WebDNA\Bundle\AppBundle\Model\Crawler\Link
 */
class LinkFactory
{
    /**
     * Build Link object from given Crawler node.
     *
     * @param  \DOMXPath   $xpath
     * @param  \DOMElement $node
     * @return WebDNA\Bundle\AppBundle\Model\Crawler\Link
     */
    public static function createFromNode(\DOMXPath $xpath, \DOMElement $node)
    {
        $img = $xpath->query('img', $node);

        if ($img->length > 0) {
            $link = new Link\ImageLink();
        } else {
            $link = new Link\TextLink();
        }

        $link->setText(trim(self::getNodeTexts($node)));
        $link->setUri($node->getAttribute('href'));
        $link->setFollow(!$node->getAttribute('rel') == 'nofollow');

        return $link;
    }

    /**
     * Get text value from nodes
     *
     * @param \DOMElement $node
     *
     * @return $text
     */
    protected static function getNodeTexts($node)
    {
        $text = $node->nodeValue;

        if ($node->hasChildNodes()) {
            foreach ($node->childNodes as $childNode) {
                $text .= self::getNodeText($childNode);
            }
        }

        return $text;
    }

    /**
     * Get text value fro node and from its attributes
     *
     * @param \DOMElement $node
     * @return string
     */
    protected static function getNodeText($node)
    {
        $allowedAttributes = ['alt', 'title'];
        $text = '';

        if ($node->hasAttributes()) {
            foreach ($node->attributes as $attr) {
                if (in_array($attr->name, $allowedAttributes)) {
                    $text .= $attr->value;
                }
            }
        }

        return $text;
    }
}
