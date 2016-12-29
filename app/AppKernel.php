<?php

use Symfony\Component\HttpKernel\Kernel;
use Symfony\Component\Config\Loader\LoaderInterface;

class AppKernel extends Kernel
{
    public function registerBundles()
    {
        $bundles = array(
            new Symfony\Bundle\FrameworkBundle\FrameworkBundle(),
            new Symfony\Bundle\SecurityBundle\SecurityBundle(),
            new Symfony\Bundle\TwigBundle\TwigBundle(),
            new Symfony\Bundle\MonologBundle\MonologBundle(),
            new Symfony\Bundle\SwiftmailerBundle\SwiftmailerBundle(),
            new Symfony\Bundle\AsseticBundle\AsseticBundle(),
            new Doctrine\Bundle\DoctrineBundle\DoctrineBundle(),
            new Sensio\Bundle\FrameworkExtraBundle\SensioFrameworkExtraBundle(),

            // Dependency bundles
            new FOS\UserBundle\FOSUserBundle(),
            new Knp\Bundle\MenuBundle\KnpMenuBundle(),
            new Knp\Bundle\PaginatorBundle\KnpPaginatorBundle(),
            new Knp\Bundle\GaufretteBundle\KnpGaufretteBundle(),
            new MZ\MailChimpBundle\MZMailChimpBundle(),
            new Oneup\UploaderBundle\OneupUploaderBundle(),
            new Lsw\MemcacheBundle\LswMemcacheBundle(),
            new Snc\RedisBundle\SncRedisBundle(),
            new OldSound\RabbitMqBundle\OldSoundRabbitMqBundle(),
            new FOS\JsRoutingBundle\FOSJsRoutingBundle(),

            // App bundles
            new WebDNA\Bundle\AppBundle\WebDNAAppBundle(),
            new WebDNA\Bundle\VerifierBundle\WebDNAVerifierBundle(),
            new WebDNA\Bundle\UserBundle\WebDNAUserBundle(),
            new WebDNA\Bundle\CommonBundle\WebDNACommonBundle(),
        );

        if (in_array($this->getEnvironment(), array('dev', 'test', 'staging'))) {
            $bundles[] = new Symfony\Bundle\WebProfilerBundle\WebProfilerBundle();
            $bundles[] = new Sensio\Bundle\DistributionBundle\SensioDistributionBundle();
            $bundles[] = new Sensio\Bundle\GeneratorBundle\SensioGeneratorBundle();
            // Dependency bundles
            $bundles[] = new Doctrine\Bundle\FixturesBundle\DoctrineFixturesBundle();
            $bundles[] = new Symfony\Bundle\DebugBundle\DebugBundle();
        }

        return $bundles;
    }

    public function registerContainerConfiguration(LoaderInterface $loader)
    {
        $loader->load(__DIR__.'/config/config_'.$this->getEnvironment().'.yml');
    }

    public function getCacheDir()
    {
        if (in_array($this->environment, array('dev'))) {
            return '/tmp/cache/' . $this->environment;
        }
        return parent::getCacheDir();
    }

    public function getLogDir()
    {
        if (in_array($this->environment, array('dev'))) {
            return '/tmp/logs/';
        }
        return parent::getLogDir();
    }

}
