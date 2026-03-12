<?php

namespace Kibatic\UX\Twig\Components;

use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;
use Symfony\UX\TwigComponent\ComponentAttributes;
use Twig\Environment;
use Twig\Runtime\EscaperRuntime;

class ButtonComponent extends AComponent
{
    public string $size = '';

    public function __construct(
        Environment $twig
    ) {
        parent::__construct($twig);

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

    public function getAttributes(): ComponentAttributes
    {
        $class = "btn btn-{$this->getType()}";

        if ($this->getSize()) {
            $class .= " btn-{$this->getSize()}";
        }

        return parent::getAttributes()->defaults(['class' => $class]);
    }
}
