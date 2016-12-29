<?php

namespace WebDNA\Bundle\AppBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

use WebDNA\Bundle\AppBundle\Entity\AnalysisProcess;
use WebDNA\Bundle\AppBundle\Event\AnalysisEvent;
use WebDNA\Bundle\AppBundle\Event\WebsiteEvent;

/**
 * Class WebsiteStatsCommand
 * @package WebDNA\Bundle\AppBundle\Command
 */
class WebsiteStatsCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('website-stats:update')
            ->setDescription('Websites stats update')
            ->addArgument('id', InputArgument::OPTIONAL, 'website id')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln('Updating stats...');

        $websitesService = $this->getContainer()->get('websites');

        $id = $input->getArgument('id');

        if ($id) {
            $websites[0] = $websitesService->find($id);
        } else {
            $websites = $websitesService->findAll();
        }

        if (count($websites)) {
            foreach ($websites as $website) {
                $output->write('website id: ' . $website->getId());
                $websiteEvent = new WebsiteEvent($this->getContainer(), $website);
                $websiteEvent->setStats();
                $output->writeln(' - done');
            }
        }

        $output->writeln('Finished');
    }
}
