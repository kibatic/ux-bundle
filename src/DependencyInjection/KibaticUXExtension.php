<?php

namespace Kibatic\UX\DependencyInjection;

use Kibatic\UX\EventListener\ResponseListener;
use Kibatic\UX\Maker\MakeCrud;
use Kibatic\UX\Turbo;
use Kibatic\UX\Twig\Components\AComponent;
use Kibatic\UX\Twig\Components\ButtonComponent;
use Symfony\Component\AssetMapper\AssetMapperInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\DependencyInjection\Extension\Extension;
use Symfony\Component\DependencyInjection\Extension\PrependExtensionInterface;
use Symfony\Component\DependencyInjection\Reference;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\UX\Autocomplete\Maker\MakeAutocompleteField;
use Twig\Environment;

class KibaticUXExtension extends Extension implements PrependExtensionInterface
{
    /**
     * @throws \Exception
     */
    public function load(array $configs, ContainerBuilder $container): void
    {
        $container->register(Turbo::class, Turbo::class)
            ->setArguments([
                new Reference('request_stack')
            ])
        ;

        $container
            ->register('kibatic.ux.make_crud', MakeCrud::class)
            ->setArguments([
                new Reference('maker.doctrine_helper', ContainerInterface::IGNORE_ON_INVALID_REFERENCE),
                new Reference('maker.renderer.form_type_renderer', ContainerInterface::IGNORE_ON_INVALID_REFERENCE),
            ])
            ->addTag('maker.command')
        ;

        $container
            ->register('kibatic.ux.twig.component.a', AComponent::class)
            ->addTag('twig.component', ['key' => 'a', 'template' => '@KibaticUX/components/a.html.twig'])
        ;

        $container
            ->register('kibatic.ux.twig.component.button', ButtonComponent::class)
            ->addTag('twig.component', ['key' => 'btn', 'template' => '@KibaticUX/components/btn.html.twig'])
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
