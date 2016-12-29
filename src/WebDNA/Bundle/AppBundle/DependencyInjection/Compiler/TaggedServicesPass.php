<?php

namespace WebDNA\Bundle\AppBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;

class TaggedServicesPass implements CompilerPassInterface
{
    /**
     * @see Symfony\Component\DependencyInjection\Compiler.CompilerPassInterface::process()
     */
    public function process(ContainerBuilder $container)
    {
    }
}
