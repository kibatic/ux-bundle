<?php

namespace Kibatic\UX;

use Kibatic\UX\DependencyInjection\KibaticUXExtension;
use Symfony\Bundle\MakerBundle\DependencyInjection\CompilerPass\MakeCommandRegistrationPass;
use Symfony\Bundle\MakerBundle\MakerInterface;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\ExtensionInterface;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use Symfony\Component\HttpKernel\Bundle\AbstractBundle;

final class KibaticUXBundle extends AbstractBundle implements CompilerPassInterface
{
    public function getContainerExtension(): ?ExtensionInterface
    {
        return new KibaticUXExtension();
    }

    public function build(ContainerBuilder $container): void
    {
        $container->addCompilerPass($this);
    }

    public function process(ContainerBuilder $container)
    {
        $container->getDefinition('twig')
            ->addMethodCall('addGlobal', ['turbo', $container->getDefinition(Turbo::class)])
        ;
    }
}
