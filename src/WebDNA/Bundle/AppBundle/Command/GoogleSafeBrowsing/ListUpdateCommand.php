<?php

namespace WebDNA\Bundle\AppBundle\Command\GoogleSafeBrowsing;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class ListUpdateCommand
 * @package WebDNA\Bundle\AppBundle\Command\GoogleSageBrowsing
 */
class ListUpdateCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('gsb:list-update')
            ->setDescription('Incremental Google Safe Browsing list update')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln('Start');

        $gsb = $this->getContainer()->get('google_safe_browsing');

        $gsb->runUpdate();
        $gsb->close();

        $output->writeln('Finished...');
    }
}
