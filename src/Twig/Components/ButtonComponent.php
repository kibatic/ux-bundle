<?php

namespace Kibatic\UX\Twig\Components;

use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;

class ButtonComponent extends AComponent
{
    public string $size = '';

    public function __construct()
    {
        parent::__construct();

        $this->type = 'primary';

        $this->superTypes['new']['type'] = 'primary';
        $this->superTypes['save']['type'] = 'primary';

        foreach ($this->superTypes as $key => $superType) {
            if (!isset($this->superTypes[$key]['type'])) {
                $this->superTypes[$key]['type'] = 'secondary';
            }
        }
    }

    public function getSize(): string
    {
        return $this->getSuperType($this->type)['size'] ?? $this->size;
    }
}
