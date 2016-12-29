<?php

namespace WebDNA\Bundle\AppBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class AnalysisRepeatConverterCommand
 * @package WebDNA\Bundle\AppBundle\Command
 */
class AnalysisRepeatConverterCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('analysis-process:repeat-convert')
            ->setDescription('Repeat analysis');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln('Repeat analyzes converter starts...');
        $table = $this->getHelperSet()->get('table');
        $table->setHeaders(array('Analysis process ID', 'created', 'repeat date', 'interval'));

        $analysisProcessService = $this->getContainer()->get('analysis_processes');

        $processes = $analysisProcessService
            ->findUnRepeatable();

        $packCnt = 100;

        $cnt = 0;
        $days = 1;

        foreach ($processes as $analysisProcess) {
            $cnt++;
            $date = new \DateTime('today');

            if ($cnt % $packCnt == 0) {
                $days++;
            }

            $startHour = $analysisProcess->getCreated()->format('H');

            $date->add(new \DateInterval('P' . $days . 'DT' . $startHour . 'H'));

            $table->addRow(array(
                $analysisProcess->getId(),
                $analysisProcess->getCreated(),
                $date->format('Y-m-d H:i:s'),
                'P' . $days . 'DT' . $startHour . 'H'
            ));

            $analysisProcess->setRepeatAt($date);
            $analysisProcess->setRepeat(1);
            $analysisProcessService->save($analysisProcess);
        }

        $table->render($output);
        $output->writeln('');
        $output->writeln('Finished at ' . date('Y-m-d H:i:s'));
    }
}
