<?php

namespace Kibatic\UX\DependencyInjection;

use Kibatic\UX\EventListener\ResponseListener;
use Kibatic\UX\Turbo;
use Symfony\Component\AssetMapper\AssetMapperInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\Extension;
use Symfony\Component\DependencyInjection\Extension\PrependExtensionInterface;
use Symfony\Component\DependencyInjection\Reference;
use Symfony\Component\HttpKernel\KernelEvents;
use Twig\Environment;

class KibaticUXExtension extends Extension implements PrependExtensionInterface
{
    /**
     * @throws \Exception
     */
    public function load(array $configs, ContainerBuilder $container): void
    {
        $container->register(ResponseListener::class)
            ->addTag('kernel.event_listener', ['event' => KernelEvents::RESPONSE])
            ->setArguments([
                new Reference(Environment::class)
            ])
        ;

        $container->register(Turbo::class, Turbo::class)
            ->setArguments([
                new Reference('request_stack')
            ])
        ;
    }

    public function prepend(ContainerBuilder $container): void
    {
        if ($this->isAssetMapperAvailable($container)) {
            $container->prependExtensionConfig('framework', [
                'asset_mapper' => [
                    'paths' => [
                        __DIR__.'/../../assets/dist' => '@kibatic/ux-bundle',
                    ],
                ],
            ]);
        }
    }

    private function isAssetMapperAvailable(ContainerBuilder $container): bool
    {
        if (!interface_exists(AssetMapperInterface::class)) {
            return false;
        }

        // check that FrameworkBundle 6.3 or higher is installed
        $bundlesMetadata = $container->getParameter('kernel.bundles_metadata');
        if (!isset($bundlesMetadata['FrameworkBundle'])) {
            return false;
        }

        return is_file($bundlesMetadata['FrameworkBundle']['path'].'/Resources/config/asset_mapper.php');
    }
}
