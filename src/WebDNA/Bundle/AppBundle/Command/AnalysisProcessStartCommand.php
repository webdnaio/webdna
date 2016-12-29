<?php

namespace WebDNA\Bundle\AppBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputArgument;

use WebDNA\Bundle\AppBundle\Entity\AnalysisProcess;
use WebDNA\Bundle\AppBundle\Event\Analysis\AnalysisWasFinishedEvent;
use WebDNA\Bundle\AppBundle\Event\Analysis\AnalysisWasStartedEvent;
use WebDNA\Bundle\AppBundle\Event\AnalysisEvents;
use WebDNA\Bundle\AppBundle\Queue\Command\Analysis\AnalyzeDomainQueueCommand;
use WebDNA\Bundle\AppBundle\Queue\Command\Analysis\AnalyzeUrlQueueCommand;

/**
 * Class AnalysisProcessStartCommand
 * @package WebDNA\Bundle\AppBundle\Command
 */
class AnalysisProcessStartCommand extends ContainerAwareCommand
{
    protected $cachedWebsites = array();

    protected function configure()
    {
        $this
            ->setName('analysis-process:start')
            ->setDescription('Start processing')
            ->addArgument(
                'verbose',
                InputArgument::OPTIONAL
            );
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $table = $this->getHelperSet()->get('table');

        ini_set('memory_limit', '14G');

        $table->setHeaders(array('Analysis process ID', 'Website name', 'Links enqueued'));

        $container = $this->getContainer();
        $analysisProcesses = $container->get('analysis_processes')
            ->findByStatus(AnalysisProcess::STATUS_READY_TO_PROCESS);
        $analysisProcessService = $container->get('analysis_processes');
        $analysisProcessUrlService = $container->get('analysis_process_urls');

        $dispatcher = $container->get('event_dispatcher');

        if (count($analysisProcesses)) {
            $output->writeln('Started at ' . date('Y-m-d H:i:s'));
            $output->writeln('');

            foreach ($analysisProcesses as $analysisProcess) {
                // Reload object from database for concurrency issues
                $analysisProcess = $container->get('analysis_processes')->find($analysisProcess->getId());

                // If analysis is demo, omit
                if ($analysisProcess->getType() == AnalysisProcess::TYPE_DISCOVER_DEMO) {
                    continue;
                }

                $counters = $container->get('analysis_process_counters_factory')->get($analysisProcess);
                $website = $analysisProcess->getWebsite();

                if ($analysisProcess->getStatus() == AnalysisProcess::STATUS_READY_TO_PROCESS) {
                    // Mark analysis as running
                    $analysisProcess->setStatus(AnalysisProcess::STATUS_PROCESSING);
                    $analysisProcessService->save($analysisProcess);

                    $dispatcher->dispatch(
                        AnalysisEvents::ANALYSIS_WAS_STARTED,
                        new AnalysisWasStartedEvent($analysisProcess)
                    );

                    // Prepare objects
                    $analysisProcessInputs = $analysisProcess->getAnalysisProcessInputs();

                    $enqueuedLinks = 0;

                    foreach ($analysisProcessInputs as $analysisProcessInput) {
                        $urlsIterator = $analysisProcessUrlService->getUrls($analysisProcessInput);

                        // Set counter to a number of urls that are going to be processed
                        $enqueuedLinks += $urlsIterator->count();

                        // For every valid link from user input, submit url analysis to proper queue
                        $producerServiceName = 'old_sound_rabbit_mq.link_analysis_producer';
                        $parallelismParamName = 'queue_link_analysis_parallelism';

                        foreach ($website->getUser()->getRoles() as $role) {
                            switch ($role) {
                                case 'ROLE_PREMIUM':
                                case 'ROLE_ADMIN':
                                    $producerServiceName = 'old_sound_rabbit_mq.internal_link_analysis_producer';
                                    $parallelismParamName = 'queue_internal_link_analysis_parallelism';

                                    break;
                            }
                        }

                        foreach ($urlsIterator as $url) {
                            $url = trim($url);
                            $container->get($producerServiceName)->publish(
                                serialize(array(
                                    'analysisProcessId' => $analysisProcess->getId(),
                                    'url' => $url,
                                )),
                                null,
                                array(),
                                $container->getParameter($parallelismParamName)
                            );

                            $counters->toProcess($url);
                        }
                    }

                    if ($enqueuedLinks == 0) {
                        $dispatcher->dispatch(
                            AnalysisEvents::ANALYSIS_WAS_FINISHED,
                            new AnalysisWasFinishedEvent($analysisProcess)
                        );
                    }

                    $table->addRow(array(
                        $analysisProcess->getId(),
                        $website->getName(),
                        $enqueuedLinks
                    ));
                }
            }

            $table->render($output);
            $output->writeln('');
            $output->writeln('Finished at ' . date('Y-m-d H:i:s'));
        }
    }
}
