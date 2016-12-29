<?php

namespace WebDNA\Bundle\AppBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class ProxyUpdateCommand
 * @package WebDNA\Bundle\AppBundle\Command
 */
class ProxyUpdateCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('proxy:update')
            ->setDescription('Update proxy list for page crawling')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln('Start');

        $addresses = $this->getContainer()->get('proxy_market')->get();

        if (count($addresses)) {
            $proxyRepository = $this->getContainer()->get('proxies');
            $table = $this->getHelperSet()->get('table');

            $table->setHeaders(array(
                'Host',
                'Port',
            ));

            $proxyRepository->deleteAll();

            foreach ($addresses as $address) {
                list($host, $port) = explode(':', $address);

                $proxy = $proxyRepository->create();

                $proxy->setHost($host);
                $proxy->setPort($port);

                $proxyRepository->save($proxy);

                $table->addRow(array(
                    $proxy->getHost(),
                    $proxy->getPort(),
                ));
            }

            $table->render($output);
        }

        $output->writeln('Finished...');
    }
}
