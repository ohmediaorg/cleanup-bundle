<?php

namespace OHMedia\CleanupBundle\DependencyInjection\Compiler;

use OHMedia\CleanupBundle\Command\CleanupCommand;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

class CleanupPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container): void
    {
        // always first check if the primary service is defined
        if (!$container->has(CleanupCommand::class)) {
            return;
        }

        $definition = $container->findDefinition(CleanupCommand::class);

        $tagged = $container->findTaggedServiceIds('oh_media_cleanup.cleaner');

        foreach ($tagged as $id => $tags) {
            $definition->addMethodCall('addCleaner', [new Reference($id)]);
        }
    }
}
