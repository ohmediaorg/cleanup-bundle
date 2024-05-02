<?php

namespace OHMedia\CleanupBundle;

use OHMedia\CleanupBundle\DependencyInjection\Compiler\CleanupPass;
use OHMedia\CleanupBundle\Interfaces\CleanerInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use Symfony\Component\HttpKernel\Bundle\AbstractBundle;

class OHMediaCleanupBundle extends AbstractBundle
{
    public function build(ContainerBuilder $container): void
    {
        parent::build($container);

        $container->addCompilerPass(new CleanupPass());
    }

    public function loadExtension(
        array $config,
        ContainerConfigurator $containerConfigurator,
        ContainerBuilder $containerBuilder
    ): void {
        $containerConfigurator->import('../config/services.yaml');

        $containerBuilder->registerForAutoconfiguration(CleanerInterface::class)
            ->addTag('oh_media_cleanup.cleaner')
        ;
    }
}
