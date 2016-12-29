<?php

namespace WebDNA\Bundle\AppBundle\Tests\UrlsFilter;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

/**
 * Class UrlsFilterTest
 * @package WebDNA\Bundle\AppBundle\Tests\Backlinks
 */
class UrlsFilterTest extends WebTestCase
{
    /**
     * UrlIterator test
     */
    public function testIterator()
    {
        $client = static::createClient(
            array(
                'environment' => 'test',
                'debug' => false,
            )
        );

        $iterator = $client->getContainer()->get('analysis_process_urls')
            ->getUrlsIterator($this->getSampleUrls());

        $this->assertEquals(6, $iterator->count());
    }

    /**
     * @return array
     */
    public function getSampleUrls()
    {
        return <<<EOT
https://www.garmin24.pl/
 https://www.google.com/android/devicemanager?hl=pl
https://www.ifopa.org/international-presidents-council.html
https://www.ipko.pl/

example.org
https://www.ipkobiznes.pl/
https://www.linkedin.com/pulse/three-times-marissa-mayer-refused-fire-thousands-yahoo-carlson?trk=tod-posts-post1-ptlt
random zzz aaa qwerty -->

EOT;
    }
}
