<?php

namespace WebDNA\Bundle\AppBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

use WebDNA\Bundle\AppBundle\Entity\AnalysisProcess;

/**
 * Class BacklinksCommand
 * @package WebDNA\Bundle\AppBundle\Command
 */
class BacklinksCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('backlinks:fetch')
            ->setDescription('Fetch backlinks for given domain')
            ->addArgument('domain', InputArgument::REQUIRED, 'domain')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln('Fetching backlinks...');

        $backlinksChain = $this->getContainer()->get('backlinks_chain');
        $domain = $input->getArgument('domain');
        $urls = $backlinksChain->runAll($domain);

        foreach ($urls as $url) {
            $output->writeln($url);
        }

        $output->writeln('Finished');
    }
}
