<?php

namespace OHMedia\CleanupBundle;

use OHMedia\CleanupBundle\DependencyInjection\Compiler\CleanupPass;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class OHMediaCleanupBundle extends Bundle
{
    public function build(ContainerBuilder $container): void
    {
        parent::build($container);

        $container->addCompilerPass(new CleanupPass());
    }
}
