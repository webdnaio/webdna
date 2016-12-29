<?php

namespace WebDNA\Bundle\AppBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;

class BacklinksProviderPass implements CompilerPassInterface
{
    /**
     * @see Symfony\Component\DependencyInjection\Compiler.CompilerPassInterface::process()
     */
    public function process(ContainerBuilder $container)
    {
        if (!$container->hasDefinition('backlinks_chain')) {
            return;
        }

        $definition = $container->getDefinition(
            'backlinks_chain'
        );

        $taggedServices = $container->findTaggedServiceIds(
            'backlinks.provider'
        );

        foreach ($taggedServices as $id => $tags) {
            foreach ($tags as $attributes) {
                $definition->addMethodCall(
                    'addProvider',
                    array(new Reference($id), $attributes["alias"])
                );
            }
        }
    }
}
