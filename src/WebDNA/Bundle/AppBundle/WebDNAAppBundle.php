<?php

namespace WebDNA\Bundle\AppBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;
use Symfony\Component\DependencyInjection\ContainerBuilder;

use WebDNA\Bundle\AppBundle\DependencyInjection\Compiler\BacklinksProviderPass;
use WebDNA\Bundle\AppBundle\DependencyInjection\Compiler\TaggedServicesPass;

class WebDNAAppBundle extends Bundle
{
    public function build(ContainerBuilder $container)
    {
        parent::build($container);

        $container->addCompilerPass(new TaggedServicesPass());
        $container->addCompilerPass(new BacklinksProviderPass());
    }
}
