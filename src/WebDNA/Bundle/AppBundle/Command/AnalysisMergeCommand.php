<?php

namespace WebDNA\Bundle\AppBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use WebDNA\Bundle\AppBundle\Entity\AnalysisProcess;

/**
 * Class AnalysisMergeCommand
 * @package WebDNA\Bundle\AppBundle\Command
 */
class AnalysisMergeCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('analysis-process:merge')
            ->setDescription('Merge analyzes');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln('Merge analyzes starts...');

        $analysisProcessService = $this->getContainer()->get('analysis_processes');
        $websitesService = $this->getContainer()->get('websites');

        $websites = $websitesService->findAllWithoutPagination();

        $output->writeln('websites count: ' . count($websites));

        foreach ($websites as $website) {
            $analysisProcesses = $website->getAnalysisProcesses();

            if (count($analysisProcesses) == 0) {
                continue;
            }

            $output->writeln('user: ' . $website->getUser()->getUsername());
            $output->writeln('website: ' . $website->getName());

            $newAnalysisProcess = $analysisProcessService->create();

            $cnt = 0;
            foreach ($analysisProcesses as $analysisProcess) {
                $analysisProcessInputs = $analysisProcess->getAnalysisProcessInputs();

                foreach ($analysisProcessInputs as $input) {
                    $cnt++;
                    $input->setAnalysisProcess($newAnalysisProcess);
                    $newAnalysisProcess->addAnalysisProcessInput($input);
                }
            }
            $output->writeln('analyzes inputs added: ' . $cnt);

            $newAnalysisProcess->setWebsite($website);
            $newAnalysisProcess->setStatus(AnalysisProcess::STATUS_READY_TO_PROCESS);
            $newAnalysisProcess->setType(AnalysisProcess::TYPE_DISCOVER);

            $analysisProcessService->save($newAnalysisProcess);
        }

        $output->writeln('');
        $output->writeln('Finished at ' . date('Y-m-d H:i:s'));
    }
}
