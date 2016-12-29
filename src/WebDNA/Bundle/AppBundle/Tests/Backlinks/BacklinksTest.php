<?php

namespace WebDNA\Bundle\AppBundle\Tests\Backlinks;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use WebDNA\Bundle\AppBundle\Entity\AnalysisProcess;

/**
 * Class BacklinksTest
 * @package WebDNA\Bundle\AppBundle\Tests\Sybilla
 */
class BacklinksTest extends WebTestCase
{

    /**
     * @var
     */
    protected $fetchLimit = 150;

    /**
     * Fetch backlinks from external API test
     *
     * @param        array $sites
     * @dataProvider sitesProvider
     */
    public function testFetchBacklinks(array $sites)
    {
        $client = static::createClient(
            array(
                'environment' => 'test',
                'debug' => true,
            )
        );

        $container = $client->getContainer();
        $consumer = $container->get('backlinks_consumer');

        foreach ($sites as $site) {
            $website = $container->get('websites')->create();
            $website->setName($site);
            $linksCount = count($consumer->getBacklinks($website));
            $this->assertEquals($this->fetchLimit, $linksCount);
        }
    }

    /**
     * Save backlinks to analysis with API test
     *
     * @param        array $sites
     * @dataProvider sitesProvider
     */
    public function testAnalysis(array $sites)
    {
        $client = static::createClient(
            array(
                'environment' => 'test',
                'debug' => true,
            )
        );

        $container = $client->getContainer();
        $consumer = $container->get('backlinks_consumer');

        $analysisProcessService = $container->get('analysis_processes');
        $websitesService = $container->get('websites');

        foreach ($sites as $site) {
            $userManager = $container->get('users');
            $user = $userManager->create();
            $user->setPlainPassword(password_hash('test_' . time(), PASSWORD_DEFAULT));
            $user->setUsername('test_' . time() . '@webdna.io');
            $user->setEmail($user->getUsername());

            $website = $websitesService->create();
            $website->setName($site);
            $website->setUser($user);
            $websitesService->save($website);

            $analysisProcess = $analysisProcessService->create();
            $analysisProcess->setWebsite($website);
            $analysisProcess->setType(AnalysisProcess::TYPE_DISCOVER_DEMO);
            $analysisProcess->setStatus(AnalysisProcess::STATUS_FETCHING_BACKLINKS);

            $analysisProcessService->save($analysisProcess);

            $consumer->saveAnalysisWithLinks($analysisProcess);

            $analysisProcessAfterSave = $analysisProcessService->find($analysisProcess->getId());
            $this->assertEquals($analysisProcessAfterSave->getStatus(), AnalysisProcess::STATUS_READY_TO_PROCESS);
        }
    }

    /**
     * @return array
     */
    public function sitesProvider()
    {
        return [
            [
                ['amiga.org'],
            ],
        ];
    }
}
