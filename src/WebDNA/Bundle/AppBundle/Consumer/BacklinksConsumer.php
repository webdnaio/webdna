<?php

namespace WebDNA\Bundle\AppBundle\Consumer;

use OldSound\RabbitMqBundle\RabbitMq\ConsumerInterface;
use PhpAmqpLib\Message\AMQPMessage;
use Psr\Log\LoggerInterface;
use WebDNA\Bundle\AppBundle\Chain\BacklinksChain;
use WebDNA\Bundle\AppBundle\Entity\AnalysisProcess;
use WebDNA\Bundle\AppBundle\Entity\Website;
use WebDNA\Bundle\AppBundle\Repository\Service\AnalysisProcessInputService;
use WebDNA\Bundle\AppBundle\Repository\Service\AnalysisProcessService;
use WebDNA\Bundle\AppBundle\Repository\Service\AnalysisProcessUrlsService;

/**
 * Class BacklinksConsumer
 * @package WebDNA\Bundle\AppBundle\Consumer
 */
class BacklinksConsumer implements ConsumerInterface
{
    /**
     * @param AnalysisProcessService $analysisProcessService
     * @param AnalysisProcessUrlsService $analysisProcessUrlsService
     * @param AnalysisProcessInputService $analysisProcessInputService,
     * @param BacklinksChain $backlinksChain
     * @param LoggerInterface $logger
     */
    public function __construct(
        AnalysisProcessService $analysisProcessService,
        AnalysisProcessUrlsService $analysisProcessUrlsService,
        AnalysisProcessInputService $analysisProcessInputService,
        BacklinksChain $backlinksChain,
        LoggerInterface $logger
    ) {
        $this->analysisProcessService = $analysisProcessService;
        $this->analysisProcessUrlsService = $analysisProcessUrlsService;
        $this->analysisProcessInputService = $analysisProcessInputService;
        $this->backlinksChain = $backlinksChain;
        $this->logger = $logger;
    }

    /**
     * @param AMQPMessage $msg
     * @return bool
     */
    public function execute(AMQPMessage $msg)
    {
        ini_set('memory_limit', '2G');

        $msgBody = unserialize($msg->body);
        $analysisProcessId = $msgBody['analysisProcessId'];

        $analysisProcess = $this->analysisProcessService->find($analysisProcessId);

        try {
            $this->saveAnalysisWithLinks($analysisProcess);
            return true;
        } catch (\Exception $e) {
            $this->logger->error(
                sprintf(
                    'Exception during processing analysis process with id %s: %s',
                    $analysisProcessId,
                    $e->getMessage()
                )
            );
            return true;
        }
    }

    /**
     * @param AnalysisProcess $analysisProcess
     * @return integer
     */
    public function saveAnalysisWithLinks(AnalysisProcess $analysisProcess)
    {
        $website = $analysisProcess->getWebsite();

        $urls = $this->getBacklinks($website);

        $analysisProcessInput = $this->analysisProcessUrlsService
            ->saveUrls($analysisProcess, $urls);
        $analysisProcess->addAnalysisProcessInput($analysisProcessInput);

        $this->analysisProcessService->addPreviousAnalysisProcessInputs($analysisProcess);

        $analysisProcess->setStatus(AnalysisProcess::STATUS_READY_TO_PROCESS);
        $this->analysisProcessService->save($analysisProcess);

        return $analysisProcess->getStatus();
    }

    /**
     * @param Website $website
     * @return array
     */
    public function getBacklinks(Website $website)
    {
        return $this->backlinksChain->runAll($website->getName());
    }
}
