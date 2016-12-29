<?php

namespace WebDNA\Bundle\AppBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

use Symfony\Component\DependencyInjection\Container;
use WebDNA\Bundle\AppBundle\Queue\Command\QueueCommand;

/**
 * Class AppConfigGenerateCommand
 * @package WebDNA\Bundle\AppBundle\Command
 */
class AppConfigGenerateCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('app:config-generate')
            ->setDescription('Generate configs')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $container = $this->getContainer();

        $this->overwriteSEOStatsApiConfig($container);
    }

    protected function overwriteSEOStatsApiConfig(Container $container)
    {
        $path = 'vendor/seostats/seostats/SEOstats/Config/ApiKeys.php';

        $mozAccessID = $container->getParameter('moz_access_id');
        $mozSecretKey = $container->getParameter('moz_secret_key');

        if (file_exists($path)) {
            $content = <<<EOT
<?php
namespace SEOstats\Config;

interface ApiKeys
{
    const GOOGLE_SIMPLE_API_ACCESS_KEY = '';
    const MOZSCAPE_ACCESS_ID  = '{$mozAccessID}';
    const MOZSCAPE_SECRET_KEY = '{$mozSecretKey}';
}
EOT;

            file_put_contents($path, $content);
        }
    }
}
