<?php

namespace Kibatic\UX;

use Kibatic\UX\DependencyInjection\KibaticUXExtension;
use Symfony\Component\DependencyInjection\Extension\ExtensionInterface;
use Symfony\Component\HttpKernel\Bundle\AbstractBundle;

final class KibaticUXBundle extends AbstractBundle
{
    public function getContainerExtension(): ?ExtensionInterface
    {
        return new KibaticUXExtension();
    }
}
