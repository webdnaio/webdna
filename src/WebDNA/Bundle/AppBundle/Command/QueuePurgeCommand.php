<?php

namespace WebDNA\Bundle\AppBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

use WebDNA\Bundle\AppBundle\Queue\Command\QueueCommand;

/**
 * Class QueuePurgeCommand
 * @package WebDNA\Bundle\AppBundle\Command
 */
class QueuePurgeCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('queue:purge')
            ->setDescription('Purge queue')
            ->addOption('queue', null, InputOption::VALUE_REQUIRED, 'Queue names to purge')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $container = $this->getContainer();
        $queue = $container->get('queue');

        // For every given queue, purge it.
        foreach (explode(',', $input->getOption('queue')) as $queueName) {
            $queue->purgeQueue($queueName);

            $output->writeln('Purged queue: ' . $queueName);
        }

        $queue->runConsumer();
    }
}
