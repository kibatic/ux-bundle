<?php

namespace Kibatic\UX\DependencyInjection;

use Kibatic\UX\EventListener\ResponseListener;
use Kibatic\UX\Turbo;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\Extension;
use Symfony\Component\DependencyInjection\Reference;
use Symfony\Component\HttpKernel\KernelEvents;
use Twig\Environment;

class KibaticUXExtension extends Extension
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

//        $container->prependExtensionConfig('framework', [
//            'asset_mapper' => [
//                'paths' => [
//                    __DIR__.'/../../assets' => '@kibatic/ux',
//                ],
//            ],
//        ]);
    }
}
