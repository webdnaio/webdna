<?php

namespace WebDNA\Bundle\AppBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

use WebDNA\Bundle\AppBundle\Entity\AnalysisProcess;

/**
 * Class AnalysisRepeatCommand
 * @package WebDNA\Bundle\AppBundle\Command
 */
class AnalysisRepeatCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('analysis-process:repeat')
            ->setDescription('Repeat analysis');
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln('Repeat analyzes process starts...');
        $table = $this->getHelperSet()->get('table');

        $analysisProcessService = $this->getContainer()->get('analysis_processes');
        $analyzesToRepeat = $analysisProcessService
            ->findAnalyzesToRepeat();

        foreach ($analyzesToRepeat as $previousAnalysisProcess) {
            $analysisProcess = $analysisProcessService->create();
            $analysisProcess->setStatus(AnalysisProcess::STATUS_FETCHING_BACKLINKS);
            $analysisProcess->setType(AnalysisProcess::TYPE_DISCOVER);
            $analysisProcess->setRepeat(1);

            $date = new \DateTime();
            $analysisProcess->setRepeatAt($date->add(new \DateInterval('P7D')));
            $analysisProcess->setWebsite($previousAnalysisProcess->getWebsite());
            $analysisProcessService->save($analysisProcess);

            $previousAnalysisProcess->setRepeat(0);
            $analysisProcessService->save($previousAnalysisProcess);

            $table->addRow(array(
                $analysisProcess->getId(),
                $analysisProcess->getWebsite()->getName()
            ));

            // delegate background task
            $this->getContainer()->get('old_sound_rabbit_mq.backlinks_producer')->publish(
                serialize(['analysisProcessId' => $analysisProcess->getId()]),
                '',
                array(),
                $this->getContainer()->getParameter('queue_backlinks_parallelism')
            );
        }

        $table->render($output);
        $output->writeln('');
        $output->writeln('Finished at ' . date('Y-m-d H:i:s'));
    }
}
