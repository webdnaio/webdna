<?php

namespace WebDNA\Bundle\AppBundle\Tests\Crawler;

use WebDNA\Bundle\AppBundle\Model\Crawler\Link;

/**
 * Class AnchorTextTest
 * @package WebDNA\Bundle\AppBundle\Tests\Crawler
 */
class AnchorTextTest extends \PHPUnit_Framework_TestCase
{

    /**
     * Link\LinkFactory::createFromNode() test
     *
     * @param        string $html         Input html
     * @param        string $expectedText Output text
     * @dataProvider additionProvider
     */
    public function testCreateFromNode($html, $expectedText)
    {
        $dom = new \DOMDocument();

        libxml_use_internal_errors(true);

        $dom->loadHTML($html);
        $xpath = new \DOMXPath($dom);

        $nodes = $xpath->query("//a");

        foreach ($nodes as $node) {
            $this->assertEquals($expectedText, Link\LinkFactory::createFromNode($xpath, $node)->getText());
        }
    }

    /**
     * @return array
     */
    public function additionProvider()
    {
        return [
            [
                '<a href="#">lorem ipsum1</a>',
                'lorem ipsum1',
            ],
            [
                '<a href="#">lorem ipsum2a <br /><span>lorem ipsum2b</span></a>',
                'lorem ipsum2a lorem ipsum2b'
            ],
            [
                '<a href="#">lorem ipsum3a <img alt="lorem ipsum3b" title="lorem ipsum3c" /></a>',
                'lorem ipsum3a lorem ipsum3blorem ipsum3c'
            ],
        ];
    }
}
