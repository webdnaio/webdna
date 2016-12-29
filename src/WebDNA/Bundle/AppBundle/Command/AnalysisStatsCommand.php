<?php

namespace WebDNA\Bundle\AppBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

use WebDNA\Bundle\AppBundle\Entity\AnalysisProcess;

/**
 * Class AnalysisStatsCommand
 * @package WebDNA\Bundle\AppBundle\Command
 */
class AnalysisStatsCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('analysis-stats:update')
            ->setDescription('Analyzes stats counter update')
            ->addArgument('id', InputArgument::OPTIONAL, 'analysis id')
            ->addOption('date-start', 'd:s', InputOption::VALUE_REQUIRED, 'date start')
            ->addOption('date-end', 'd:e', InputOption::VALUE_REQUIRED, 'date end')
            ->addOption('skip-update-page-metrics', 's:u', InputOption::VALUE_NONE, 'skip page metrics update')
            ->addOption('force-update-page-metrics', 'f:u', InputOption::VALUE_NONE, 'force update mage metrics')
            ->addOption('username', 'u', InputOption::VALUE_REQUIRED, 'update only for given username')
            ->addOption('users-with-role', 'u:w:r', InputOption::VALUE_REQUIRED, 'update only users with role premium')
            ->addOption(
                'skip-stats-update',
                's:s:u',
                InputOption::VALUE_NONE,
                'skip update of analysis process statistics'
            );
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        ini_set('memory_limit', '14G');

        $output->writeln('Updating stats...');

        $analysisProcessesService = $this->getContainer()->get('analysis_processes');

        $id = $input->getArgument('id');

        if (is_numeric($id)) {
            $this->updateAnalysisProcesses([$analysisProcessesService->find((int)$id)], $input, $output);
        } else {
            if (strtotime($input->getOption('date-start')) > 0 && strtotime($input->getOption('date-end')) > 0) {
                $analysisProcesses = $analysisProcessesService
                    ->findByStatusWithDateRange(
                        AnalysisProcess::STATUS_COMPLETED,
                        new \DateTime($input->getOption('date-start') . ' 00:00:00'),
                        new \DateTime($input->getOption('date-end') . ' 23:59:59')
                    );
                $this->updateAnalysisProcesses($analysisProcesses, $input, $output);

                // update analysis process stats by user role
            } elseif ($input->getOption('users-with-role') != '') {
                $users = $this->getContainer()->get('users')->findByRole($input->getOption('users-with-role'));
                foreach ($users as $user) {
                    $websites = $this->getContainer()->get('websites')->findAllUserWebsitesWithoutPagination($user);

                    foreach ($websites as $website) {
                        $this->updateAnalysisProcesses($website->getAnalysisProcesses(), $input, $output);
                    }
                }

                // update analysis process stats by username (email)
            } elseif ($input->getOption('username') != '') {
                $user = $this->getContainer()->get('users')->findByEmail($input->getOption('username'));
                $websites = $this->getContainer()->get('websites')->findAllUserWebsitesWithoutPagination($user);

                foreach ($websites as $website) {
                    $this->updateAnalysisProcesses($website->getAnalysisProcesses(), $input, $output);
                }

                // update all by status completed
            } else {
                $analysisProcesses = $analysisProcessesService->findByStatus(AnalysisProcess::STATUS_COMPLETED);
                $this->updateAnalysisProcesses($analysisProcesses, $input, $output);
            }
        }


        $output->writeln('Finished');
    }

    /**
     * @param AnalysisProcess[] $analysisProcesses
     * @param InputInterface $input
     * @param OutputInterface $output
     */
    protected function updateAnalysisProcesses($analysisProcesses, InputInterface $input, OutputInterface $output)
    {
        $analysisProcessStatsService = $this->getContainer()->get('analysis_processes_stats');
        $pageMetricsService = $this->getContainer()->get('page_metrics');

        $em = $this->getContainer()->get('doctrine')->getManager();

        if (count($analysisProcesses)) {
            foreach ($analysisProcesses as $analysisProcess) {
                $output->writeln('process id: ' . $analysisProcess->getId());

                if (!$input->getOption('skip-stats-update')) {
                    $analysisProcessStatsService->setStatsCounters($analysisProcess);
                    $em->flush();
                    $em->clear();
                }

                if (!$input->getOption('skip-update-page-metrics')) {
                    $itemAnalyzesIds = $this->getContainer()->get('item_analyzes')->getItemAnalyzesIds(
                        $analysisProcess
                    );

                    foreach ($itemAnalyzesIds as $itemAnalysisId) {
                        $itemAnalysis = $this->getContainer()->get('item_analyzes')->find($itemAnalysisId);
                        $pageMetric = $pageMetricsService->getMetricByItemAnalysis($itemAnalysis);
                        if (empty($pageMetric) && !$input->getOption('force-update-page-metrics')) {
                            continue;
                        }
                        $output->writeln('item: ' . $itemAnalysis->getId());
                        $pageMetricsService->save($pageMetric);

                        $em->flush();
                        $em->clear();
                        //$em->clear($pageMetric);
                        //$em->clear($itemAnalysis);
                    }
                }

                $em->flush();
                $em->clear($analysisProcess);
                $output->writeln(' - done');
            }
        }
    }
}
