<?php

namespace WebDNA\Bundle\AppBundle\Tests\Sybilla;

use PhpAmqpLib\Message\AMQPMessage;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use WebDNA\Bundle\AppBundle\Consumer\LinkAnalysisConsumer;
use WebDNA\Bundle\AppBundle\Entity\AnalysisProcess;
use WebDNA\Bundle\AppBundle\Entity\Page;

/**
 * Class ClassificationTest
 * @package WebDNA\Bundle\AppBundle\Tests\Sybilla
 */
class ClassificationTest extends WebTestCase
{

    /**
     * Sybilla classification test
     *
     * @param        array $urls
     * @param        array $expectedResult
     * @dataProvider additionProvider
     */
    public function testClassify(array $urls, $expectedResult)
    {
        $client = static::createClient(
            array(
                'environment' => 'test',
                'debug' => false,
            )
        );

        $container = $client->getContainer();

        $consumer = new LinkAnalysisConsumer($container);
        $analysisProcessService = $container->get('analysis_processes');
        $websitesService = $container->get('websites');

        foreach ($urls as $urlData) {
            $userManager = $container->get('users');
            $user = $userManager->create();
            $user->setPlainPassword(password_hash('test_' . time(), PASSWORD_DEFAULT));
            $user->setUsername('test_' . time() . '@webdna.io');
            $user->setEmail($user->getUsername());

            $website = $websitesService->create();
            $website->setName($urlData['website_url']);
            $website->setUser($user);
            $websitesService->save($website);

            $analysisProcess = $analysisProcessService->create();
            $analysisProcess->setWebsite($website);
            $analysisProcess->setType(AnalysisProcess::TYPE_DISCOVER_DEMO);
            $analysisProcess->setStatus(AnalysisProcess::STATUS_PROCESSING);

            $analysisProcessService->save($analysisProcess);

            $msg = new AMQPMessage(
                serialize(['analysisProcessId' => $analysisProcess->getId(), 'url' => $urlData['page_url']])
            );

            $consumer->execute($msg);

            $page = $container->get('pages')->findByWebsiteAndUrl($website, $urlData['page_url']);

            $this->assertEquals($page->getClassSystem(), Page::CLASS_POSITIVE);
        }
    }

    /**
     * @return array
     */
    public function additionProvider()
    {
        return [
            [
                [
                    ['website_url' => 'http://webdna.io', 'page_url' => 'https://www.google.com']
                ],
                ['positive'],
            ],
        ];
    }
}
