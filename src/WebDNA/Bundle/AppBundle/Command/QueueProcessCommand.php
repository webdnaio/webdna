<?php

namespace WebDNA\Bundle\AppBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

use WebDNA\Bundle\AppBundle\Queue\Command\QueueCommand;

/**
 * Class QueueProcessCommand
 * @package WebDNA\Bundle\AppBundle\Command
 */
class QueueProcessCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('queue:process')
            ->setDescription('Process queue')
            ->addOption('queue', null, InputOption::VALUE_REQUIRED, 'Queue names to process')
            ->addOption('failed-queue', null, InputOption::VALUE_REQUIRED, 'Failed queue names')
            ->addOption('aborted-queue', null, InputOption::VALUE_REQUIRED, 'Aborted queue names')
            ->addOption('retries', null, InputOption::VALUE_REQUIRED, 'Command retries before abort', 3)
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln('Started at ' . date('Y-m-d H:i:s'));

        $container = $this->getContainer();
        $queue = $container->get('queue');
        $maxRetries = $input->getOption('retries');

        // For every given queue, register consumer with processing callback.
        foreach (explode(',', $input->getOption('queue')) as $queueName) {
            $queue->registerConsumer($queueName, function ($command) use (
                $container,
                $queue,
                $maxRetries,
                $input,
                $output
            ) {
                $processed = false;

                $command->increaseExecutionCounter();

                $output->writeln(get_class($command) . ' ' . date('Y-m-d H:i:s'));

                try {
                    $originCommand = clone $command;

                    if ($command instanceof QueueCommand) {
                        $command->setup($container);

                        if (!($processed = $command->execute())) {
                        }
                    } else {
                        $output->writeln('Given object is not an instance of QueueCommand but ' . get_class($command));
                    }
                } catch (\Exception $e) {
                    $output->writeln($e->getMessage());
                }

                if (!$processed) {
                    if ($maxRetries > 0) {
                        $name = $originCommand->getExecutionCounter() < $maxRetries
                            ? $input->getOption('failed-queue')
                            : $input->getOption('aborted-queue');

                        $container->get($name)->publish($originCommand);
                    }
                }

                if (rand(0, 15) == 0) {
                    exit;
                }
            });

            $output->writeln('Registered consumer for queue: ' . $queueName);
        }

        $queue->runConsumer();
    }
}
