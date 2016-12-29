<?php

namespace WebDNA\Bundle\AppBundle\Model;

use WebDNA\Bundle\AppBundle\Entity\AnalysisProcess;
use WebDNA\Bundle\AppBundle\Entity\Website;
use WebDNA\Bundle\AppBundle\Queue\Command\Analysis\AnalyzeDomainQueueCommand;
use WebDNA\Bundle\AppBundle\Queue\Command\Analysis\AnalyzeUrlQueueCommand;
use WebDNA\Bundle\AppBundle\Queue\CommandQueueService;
use WebDNA\Bundle\AppBundle\Repository\Service\AnalysisProcessService;
use WebDNA\Bundle\AppBundle\Repository\Service\WebsiteService;
use WebDNA\Bundle\UserBundle\Entity\User;
use WebDNA\Bundle\UserBundle\Repository\Service\UserService;

/**
 * Class LinkAnalysisService
 * @package WebDNA\Bundle\AppBundle\Model
 */
class LinkAnalysisService
{
    /**
     * @var
     */
    protected $systemUser;

    /**
     * @param WebsiteService $websiteService
     * @param AnalysisProcessService $analysisProcessService
     * @param UserService $userService
     * @param CommandQueueService $domainUserAnalysisQueue
     * @param CommandQueueService $domainSystemAnalysisQueue
     * @param CommandQueueService $urlAnalysisQueue
     */
    public function __construct(
        WebsiteService $websiteService,
        AnalysisProcessService $analysisProcessService,
        UserService $userService,
        CommandQueueService $domainUserAnalysisQueue,
        CommandQueueService $domainSystemAnalysisQueue,
        CommandQueueService $urlAnalysisQueue
    ) {
        $this->websiteService = $websiteService;
        $this->analysisProcessService = $analysisProcessService;
        $this->userService = $userService;
        $this->domainUserAnalysisQueue = $domainUserAnalysisQueue;
        $this->domainSystemAnalysisQueue = $domainSystemAnalysisQueue;
        $this->urlAnalysisQueue = $urlAnalysisQueue;
    }

    /**
     * @param AnalysisProcess $analysisProcess
     * @param $mode
     */
    public function analyzeUserDomain(AnalysisProcess $analysisProcess)
    {
        return $this->domainUserAnalysisQueue->publish(
            new AnalyzeDomainQueueCommand(
                $analysisProcess->getId(),
                AnalyzeDomainQueueCommand::MODE_USER
            )
        );
    }

    /**
     * @param AnalysisProcess $analysisProcess
     * @param $mode
     */
    public function analyzeSystemDomain(AnalysisProcess $analysisProcess)
    {
        return $this->domainSystemAnalysisQueue->publish(
            new AnalyzeDomainQueueCommand(
                $analysisProcess->getId(),
                AnalyzeDomainQueueCommand::MODE_SYSTEM
            )
        );
    }

    /**
     * @param $url
     */
    public function analyzeUrl(AnalysisProcess $analysisProcess, $url)
    {
        $website = $analysisProcess->getWebsite();
        $domain = parse_url($url, PHP_URL_HOST);
        $urlWebsite = $this->getCachedSystemUserWebsiteByName($domain);

        if (!($urlWebsite instanceof Website)) {
            // If there is no website associated with analyzed url
            // we have to create object and analyze domain (short version)
            $urlWebsite = $this->websiteService->create();
            $urlWebsiteAnalysisProcess = $this->analysisProcessService->create();

            $urlWebsiteAnalysisProcess->setType(AnalysisProcess::TYPE_DISCOVER_DEMO);

            $urlWebsite->setUser($this->getSystemUser());
            $urlWebsite->setName($domain);

            // We have to save website first, there are some problems with cascade persist.
            $this->websiteService->save($urlWebsite);

            $urlWebsite->addAnalysisProcess($urlWebsiteAnalysisProcess);
            $urlWebsiteAnalysisProcess->setWebsite($urlWebsite);

            $this->websiteService->save($urlWebsite);
            $this->analysisProcessService->save($urlWebsiteAnalysisProcess);

            $this->analyzeSystemDomain($urlWebsiteAnalysisProcess);

            // For better Doctrine performance, we have to detach
            // created objects from Doctrine manager.
            // $container->get('doctrine')->getManager()->detach($urlWebsiteAnalysisProcess);
        }

        // $container->get('doctrine')->getManager()->detach($urlWebsite);

        // Submit analysis command
        $this->urlAnalysisQueue->publish(
            new AnalyzeUrlQueueCommand($analysisProcess->getId(), $website->getName(), $url)
        );
    }

    /**
     * Helper method for more efficient retrieving objects.
     *
     * @todo For future: add cache size limit, if needed
     *
     * @param WebsiteService $websiteService
     * @param $doctrine
     * @param $domain
     * @param $user
     * @return Website
     */
    protected function getCachedSystemUserWebsiteByName($domain)
    {
        // Get from local cache array of fetch object
        // from service and put into cache
        if (isset($this->cachedWebsites[$domain])) {
            $website = $this->cachedWebsites[$domain];
        } else {
            $website = $this->websiteService->findUserWebsiteByName($domain, $this->getSystemUser());

            if ($website instanceof Website) {
                $this->cachedWebsites[$domain] = $website;
            }
        }

        return $website;
    }

    /**
     * @return User
     */
    protected function getSystemUser()
    {
        if (!($this->systemUser instanceof User)) {
            $this->systemUser = $this->userService->findByUsername('system@webdna.io');
        }

        return $this->systemUser;
    }
}
