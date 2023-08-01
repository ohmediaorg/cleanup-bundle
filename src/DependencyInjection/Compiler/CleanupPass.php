<?php

namespace OHMedia\CleanupBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

class CleanupPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container)
    {
        // always first check if the primary service is defined
        if (!$container->has('oh_media_cleanup.command')) {
            return;
        }

        $definition = $container->findDefinition('oh_media_cleanup.command');

        $tagged = $container->findTaggedServiceIds('oh_media_cleanup.cleaner');

        foreach ($tagged as $id => $tags) {
            $definition->addMethodCall('addCleaner', [new Reference($id)]);
        }
    }
}
