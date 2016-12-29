<?php

namespace WebDNA\Bundle\AppBundle\Consumer;

use Buzz\Browser;
use Buzz\Client\Curl;
use Doctrine\Common\Collections\ArrayCollection;
use OldSound\RabbitMqBundle\RabbitMq\ConsumerInterface;
use PhpAmqpLib\Message\AMQPMessage;
use Symfony\Component\DependencyInjection\Container;
use WebDNA\Bundle\AppBundle\Entity\AnalysisProcess;
use WebDNA\Bundle\AppBundle\Entity\ItemAnalysis;
use WebDNA\Bundle\AppBundle\Entity\Link;
use WebDNA\Bundle\AppBundle\Entity\Notification;
use WebDNA\Bundle\AppBundle\Entity\Page;
use WebDNA\Bundle\AppBundle\Entity\Website;
use WebDNA\Bundle\AppBundle\Entity\WebsiteUserClassification;
use WebDNA\Bundle\AppBundle\Event\Analysis\AnalysisWasFinishedEvent;
use WebDNA\Bundle\AppBundle\Event\Analysis\PageWasClassifiedNegativeEvent;
use WebDNA\Bundle\AppBundle\Event\Analysis\PageWasClassifiedPositiveEvent;
use WebDNA\Bundle\AppBundle\Event\Analysis\PageWasClassifiedSuspiciousEvent;
use WebDNA\Bundle\AppBundle\Event\Analysis\PageWasClassifiedUnclassifiedEvent;
use WebDNA\Bundle\AppBundle\Event\Analysis\PageWasClassifiedUnknownEvent;
use WebDNA\Bundle\AppBundle\Event\Analysis\PageWasCreatedEvent;
use WebDNA\Bundle\AppBundle\Event\AnalysisEvents;
use WebDNA\Bundle\AppBundle\Model\CharsetTable;
use WebDNA\Bundle\AppBundle\Model\Crawler\DOMCrawler;
use WebDNA\Bundle\AppBundle\Model\ItemMetric\Builder\DocumentExternalLinksDistributionIMBuilder;
use WebDNA\Bundle\AppBundle\Model\ItemMetric\Builder\DocumentHtmlMetaIMBuilder;
use WebDNA\Bundle\AppBundle\Model\ItemMetric\Builder\DocumentHtmlTextStructureIMBuilder;
use WebDNA\Bundle\AppBundle\Model\ItemMetric\Builder\DocumentLinksAttrFollowIMBuilder;
use WebDNA\Bundle\AppBundle\Model\ItemMetric\Builder\DocumentLinksDistributionIMBuilder;
use WebDNA\Bundle\AppBundle\Model\ItemMetric\Builder\DocumentWebsiteLinksDistributionIMBuilder;
use WebDNA\Bundle\AppBundle\Model\ItemMetric\Builder\DomainMOZIMBuilder;
use WebDNA\Bundle\AppBundle\Model\ItemMetric\Builder\UrlInternetArchiveIMBuilder;
use WebDNA\Bundle\AppBundle\Model\ItemMetric\Builder\UrlPerformanceIMBuilder;
use WebDNA\Bundle\AppBundle\Model\ItemMetric\Builder\UrlSecurityIMBuilder;
use WebDNA\Bundle\AppBundle\Event\AnalysisEvent;
use WebDNA\Bundle\UserBundle\Entity\User;

/**
 * Class LinkAnalysisConsumer
 * @package WebDNA\Bundle\AppBundle\Consumer
 */
class LinkAnalysisConsumer implements ConsumerInterface
{
    /**
     * @var Container
     */
    protected $container;

    /**
     * @param Container $container
     */
    public function __construct(Container $container)
    {
        $this->container = $container;
    }

    /**
     * @param AMQPMessage $msg
     * @return bool
     */
    public function execute(AMQPMessage $msg)
    {
        ini_set('memory_limit', '4G');

        $data = unserialize($msg->body);
        $url = $data['url'];

        $em = $this->container->get('doctrine')->getManager();

        try {
            // Fetch needed objects.
            $analysisProcess = $this->container->get('analysis_processes')->find($data['analysisProcessId']);

            if (is_null($analysisProcess)) {
                return true;
            }

            $domain = parse_url($url, PHP_URL_HOST);

            $counters = $this->container->get('analysis_process_counters_factory')->get($analysisProcess);

            // First part on url checking, url domain metrics.
            while (true) {
                $website = $this->getWebsite($domain);

                if ($website instanceof Website) {
                    if ($this->isWebsiteProcessed($website)) {
                        break;
                    }
                }

                $lock = $this->container->get('processing_lock_factory')->get($domain);

                if ($lock->acquire()) {
                    if (!($website instanceof Website)) {
                        $website = $this->createWebsite($domain);
                    }

                    $websiteMetrics = $this->processWebsiteMetrics($analysisProcess, $website);

                    if (count($websiteMetrics)) {
                        $lock->delete();
                    } else {
                        throw new \LogicException();
                    }

                    // Temporary sleep for free MOZ usage ;)
                    sleep(rand(5, 10));
                } else {
                    sleep(rand(1, 2));
                }
            }

            // Second part of url checking, url metrics.
            for ($i = 0; $i < 3; $i++) {
                // First call without proxy, all others with proxy.
                $browser = $this->prepareBrowser($i > 0);
                $response = $browser->get($url);
                $crawler = new DOMCrawler($response->getContent());

                // When request was forbidden or invalid, repeat with proxy.
                if (!($response->isInvalid() || $response->isForbidden())) {
                    break;
                }
            }

            $page = $this->container->get('pages')->findByWebsiteAndUrl($website, $url);

            if (!($page instanceof Page)) {
                $page = $this->createPage($website, $url);

                $this->container->get('event_dispatcher')->dispatch(
                    AnalysisEvents::ANALYSIS_PAGE_WAS_CREATED,
                    new PageWasCreatedEvent($analysisProcess, $page)
                );
            }

            $analysisWebsite = $analysisProcess->getWebsite();
            $page = $this->processPage($analysisWebsite, $website, $page, $browser, $response, $crawler);

            $pageMetrics = $this->processUrlMetrics(
                $analysisProcess,
                $analysisWebsite,
                $website,
                $page,
                $browser,
                $crawler
            );

            // Metrics collection.
            $metrics = $this->prepareMetrics($page);

            if (count($metrics)) {
                // Sybilla classification.
                $this->classifyPage($page, $metrics);

                // Custom user classification for whole website.
                $this->classifyPageWithWebsiteUserClassification($page, $website, $analysisWebsite->getUser());

                switch ($page->getItemAnalysis()->getClassSystem()) {
                    case ItemAnalysis::CLASS_NEGATIVE:
                        $this->container->get('event_dispatcher')->dispatch(
                            AnalysisEvents::ANALYSIS_PAGE_CLASSIFIED_NEGATIVE,
                            new PageWasClassifiedNegativeEvent($analysisProcess, $page)
                        );

                        break;

                    case ItemAnalysis::CLASS_POSITIVE:
                        $this->container->get('event_dispatcher')->dispatch(
                            AnalysisEvents::ANALYSIS_PAGE_CLASSIFIED_POSITIVE,
                            new PageWasClassifiedPositiveEvent($analysisProcess, $page)
                        );

                        break;

                    case ItemAnalysis::CLASS_SUSPICIOUS:
                        $this->container->get('event_dispatcher')->dispatch(
                            AnalysisEvents::ANALYSIS_PAGE_CLASSIFIED_SUSPICIOUS,
                            new PageWasClassifiedSuspiciousEvent($analysisProcess, $page)
                        );

                        break;

                    case ItemAnalysis::CLASS_UNCLASSIFIED:
                        $this->container->get('event_dispatcher')->dispatch(
                            AnalysisEvents::ANALYSIS_PAGE_CLASSIFIED_UNCLASSIFIED,
                            new PageWasClassifiedUnclassifiedEvent($analysisProcess, $page)
                        );

                        break;

                    case ItemAnalysis::CLASS_UNKNOWN:
                        $this->container->get('event_dispatcher')->dispatch(
                            AnalysisEvents::ANALYSIS_PAGE_CLASSIFIED_UNKNOWN,
                            new PageWasClassifiedUnknownEvent($analysisProcess, $page)
                        );

                        break;
                }

                // Counters update.
                $counters->processed($url);

                // Page processing status update
                $status = Page::STATUS_PROCESSED;
            } else {
                throw new \LogicException();
            }
        } catch (\Exception $e) {
            $this->container->get('logger')->error(
                sprintf(
                    'Exception during processing %s: %s',
                    $url,
                    $e->getMessage()
                )
            );

            $status = Page::STATUS_FAILED;

            // Counters update.
            $counters->failed($url);
        }

        // Page processing status update.
        if (isset($page) && $page instanceof Page) {
            $page->setStatus($status);

            $this->container->get('pages')->save($page);
        }

        // Analysis completeness checking.
        if (isset($counters) && $counters->isCompleted()) {
            $counters->delete();

            // Change analysis process status
            if (isset($analysisProcess)) {
                $analysisProcess->setStatus(AnalysisProcess::STATUS_COMPLETED);
                $analysisProcess->setFinished(new \DateTime('now'));

                $this->container->get('analysis_processes')->save($analysisProcess);

                // Notify others... Via event for example.
                // @todo trigger event only once (i.e. email message should be sent only once)
                $this->container->get('event_dispatcher')->dispatch(
                    AnalysisEvents::ANALYSIS_WAS_FINISHED,
                    new AnalysisWasFinishedEvent($analysisProcess)
                );
            }
        }

        // Flush doctrine cache.
        $em->flush();
        $em->clear();

        // Message should be always removed from queue.
        // When something went wrong, page status will be updated
        // and further actions will be executed from cron commands.
        return true;
    }

    /**
     * @todo Move to DI container
     *
     * @param $withProxy
     * @return Browser
     */
    protected function prepareBrowser($withProxy = false)
    {
        $client = new Curl();
        $proxies = $this->container->get('proxies')->findBy(array());

        $client->setTimeout(30);
        $client->setMaxRedirects(10);

        if (count($proxies) && $withProxy) {
            shuffle($proxies);

            $client->setProxy($proxies[0]->getAddress());
        }

        return new Browser($client);
    }

    /**
     * @param Website $website
     * @param string $url
     * @return Page
     */
    protected function createPage(Website $website, $url)
    {
        $page = $this->container->get('pages')->create();

        $page->setWebsite($website);
        $page->setType(Page::TYPE_EXTERNAL);
        $page->setStatus(Page::STATUS_NEW);
        $page->setUrl($url);
        $website->addPage($page);

        $this->container->get('pages')->save($page);
        $this->container->get('websites')->save($website);

        return $page;
    }

    /**
     * @param Website $analysisWebsite
     * @param Website $website
     * @param Page $page
     * @param Browser $browser
     * @param $response
     * @param DOMCrawler $crawler
     * @return Page
     */
    protected function processPage(
        Website $analysisWebsite,
        Website $website,
        Page $page,
        Browser $browser,
        $response,
        DOMCrawler $crawler
    ) {
        $client = $browser->getClient();
        $charsetTable = new CharsetTable();

        $page->setHttpCode($response->getStatusCode());
        $page->setEffectiveUrl($client->getInfo(CURLINFO_EFFECTIVE_URL));
        $page->setMetaTitle($crawler->getMetaTitle() ?: '');
        $page->setMetaDescription($crawler->getMetaDescription() ?: '');
        $page->setMetaKeywords($crawler->getMetaKeywords() ?: '');
        $page->setMetaCharset($charsetTable->getCharsetId($crawler->getMetaCharset()));

        $this->container->get('pages')->save($page);

        if ($response->isSuccessful()) {
            // Process links from analyzed page
            $links = $crawler->getLinks();

            $analysisWebsiteLinks = $links->filterDomainLinks($analysisWebsite->getName());

            foreach ($analysisWebsiteLinks as $domLink) {
                $link = $this->container->get('links')->create();

                $link->setSourcePage($page);
                $link->setType($domLink->getType());
                $link->setStatus(Link::STATUS_NEW);
                $link->setUrl($domLink->getUri());
                $link->setAnchor((string)$domLink->getText());
                $page->addLink($link);
                $analysisWebsite->addLink($link);

                $this->container->get('links')->save($link);
            }

            $this->container->get('pages')->save($page);
            $this->container->get('websites')->save($analysisWebsite);
        }

        return $page;
    }

    /**
     * @param $domain
     * @return Website
     */
    protected function getWebsite($domain)
    {
        return $this->container->get('websites')->findUserWebsiteByName(
            $domain,
            $this->container->get('users')->findByUsername('system@webdna.io')
        );
    }

    /**
     * @param $domain
     * @return mixed
     */
    protected function createWebsite($domain)
    {
        $website = $this->container->get('websites')->create();

        $website->setName($domain);
        $website->setUser($this->container->get('users')->findByUsername('system@webdna.io'));

        $this->container->get('websites')->save($website);

        return $website;
    }

    /**
     * @param Website $website
     * @return bool
     */
    protected function isWebsiteProcessed(Website $website)
    {
        return $website->hasValidItemAnalysis(30);
    }

    /**
     * @param AnalysisProcess $analysisProcess
     * @param Website $website
     * @return array
     */
    protected function processWebsiteMetrics(AnalysisProcess $analysisProcess, Website $website)
    {
        $builders = array(
            new DomainMOZIMBuilder($this->container->get('url_metrics_moz'), $website),
            new UrlInternetArchiveIMBuilder($this->container->get('api_client_webarchive'), $website),
        );
        $metrics = array();

        foreach ($builders as $builder) {
            $metric = $builder->build();

            if ($builder->isValid($metric)) {
                $metrics[] = $metric;
            } else {
                throw new \LogicException('');
            }
        }

        try {
            $itemAnalysis = $this->container->get('item_analyzes')->create();

            $itemAnalysis->setObject($website);
            $itemAnalysis->setAnalysisProcess($analysisProcess);
            $website->setItemAnalysis($itemAnalysis);
            $website->addItemAnalysis($itemAnalysis);

            $this->container->get('item_analyzes')->save($itemAnalysis);

            foreach ($metrics as $metric) {
                $itemAnalysis->addItemMetric($metric);

                $this->container->get('item_metrics')->save($metric);
            }

            $itemAnalysis->setStatus(ItemAnalysis::STATUS_COMPLETED);

            $this->container->get('item_analyzes')->save($itemAnalysis);
            $this->container->get('websites')->save($website);
        } catch (\Exception $e) {
            // Rollback;

            throw new \LogicException('');
        }

        return $metrics;
    }

    /**
     * @param AnalysisProcess $analysisProcess
     * @param Website $analysisWebsite
     * @param Website $website
     * @param Page $page
     * @param Browser $browser
     * @param $crawler
     * @return array
     */
    protected function processUrlMetrics(
        AnalysisProcess $analysisProcess,
        Website $analysisWebsite,
        Website $website,
        Page $page,
        Browser $browser,
        DOMCrawler $crawler
    ) {
        $links = $crawler->getLinks();

        $builders = array(
            new DocumentHtmlTextStructureIMBuilder($crawler),
            new DocumentLinksAttrFollowIMBuilder($links, $analysisWebsite),
            new DocumentWebsiteLinksDistributionIMBuilder($crawler, $links, $analysisWebsite),
            new DocumentLinksDistributionIMBuilder($crawler, $links),
            new DocumentExternalLinksDistributionIMBuilder($crawler, $links, $website->getName()),
            new DocumentHtmlMetaIMBuilder($crawler),
            new UrlPerformanceIMBuilder($browser->getClient()),
            new UrlSecurityIMBuilder($this->container->get('google_safe_browsing'), $page->getEffectiveUrl()),
        );
        $metrics = array();

        foreach ($builders as $builder) {
            $metric = $builder->build();

            if ($builder->isValid($metric)) {
                $metrics[] = $metric;
            } else {
                throw new \LogicException('');
            }
        }

        try {
            $itemAnalysis = $this->container->get('item_analyzes')->create();

            $itemAnalysis->setObject($page);
            $itemAnalysis->setAnalysisProcess($analysisProcess);
            $analysisProcess->addItemAnalysis($itemAnalysis);

            $this->container->get('item_analyzes')->save($itemAnalysis);

            $page->setItemAnalysis($itemAnalysis);

            $this->container->get('pages')->save($page);

            // Add item metrics
            foreach ($metrics as $metric) {
                $itemAnalysis->addItemMetric($metric);

                $this->container->get('item_metrics')->save($metric);
            }

            // Save page metric from item metrics
            $pageMetricsService = $this->container->get('page_metrics');
            $pageMetric = $pageMetricsService->getMetricByItemAnalysis($itemAnalysis);
            $pageMetricsService->save($pageMetric);

            $itemAnalysis->setStatus(ItemAnalysis::STATUS_COMPLETED);

            $this->container->get('item_analyzes')->save($itemAnalysis);
        } catch (\Exception $e) {
            // Rollback.

            $this->container->get('logger')->error(
                sprintf(
                    '[processUrlMetrics] Exception during processing %s: %s',
                    $page->getUrl(),
                    $e->getMessage()
                )
            );

            throw new \LogicException('');
        }

        return $metrics;
    }

    /**
     * @param Page $page
     * @return ArrayCollection
     */
    protected function prepareMetrics(Page $page)
    {
        $metrics = new ArrayCollection();
        $pageItemAnalysis = $page->getItemAnalysis();

        if ($pageItemAnalysis instanceof ItemAnalysis) {
            foreach ($pageItemAnalysis->getItemMetrics() as $metric) {
                $metrics->add($metric);
            }
        }

        $websiteItemAnalysis = $page->getWebsite()->getItemAnalysis();

        if ($websiteItemAnalysis instanceof ItemAnalysis) {
            foreach ($websiteItemAnalysis->getItemMetrics() as $metric) {
                $metrics->add($metric);
            }
        }

        return $metrics;
    }

    /**
     * @param Page $page
     * @param $metrics
     */
    protected function classifyPage(Page $page, $metrics)
    {
        $classification = null;
        $pageItemAnalysis = $page->getItemAnalysis();

        for ($i = 0; $i < 3; $i++) {
            try {
                $classification = $this->container->get('sybilla_client')->classify($page, $metrics);

                break;
            } catch (\Exception $e) {
                sleep(3);
            }
        }

        if (is_array($classification)) {
            $pageItemAnalysis->setClassNegativeSimilarity($classification['negative']);
            $pageItemAnalysis->setClassPositiveSimilarity($classification['positive']);

            $class = ItemAnalysis::CLASS_POSITIVE;

            foreach (ItemAnalysis::$CLASS_RANGES as $selected_class => $range) {
                if (
                    $pageItemAnalysis->getClassPositiveSimilarity() >= $range[0]
                    &&
                    $pageItemAnalysis->getClassPositiveSimilarity() < $range[1]
                ) {
                    $class = $selected_class;
                    break;
                }
            }
        } else {
            $class = ItemAnalysis::CLASS_UNKNOWN;
        }

        $pageItemAnalysis->setClassSystem($class);

        $this->container->get('item_analyzes')->save($pageItemAnalysis);
    }

    /**
     * @param Page $page
     * @param Website $website
     * @param User $user
     */
    protected function classifyPageWithWebsiteUserClassification(Page $page, Website $website, User $user)
    {
        $pageItemAnalysis = $page->getItemAnalysis();
        $websiteUserClassification = $this->container
            ->get('website_user_classifications')
            ->findUserWebsiteClassification($website, $user);

        if ($websiteUserClassification instanceof WebsiteUserClassification) {
            $pageItemAnalysis->setClassUser($websiteUserClassification->getClass());

            $this->container->get('item_analyzes')->save($pageItemAnalysis);
        }
    }
}
