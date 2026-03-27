<?php

namespace Kibatic\UX\Twig\Components;

use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;
use Symfony\UX\TwigComponent\ComponentAttributes;
use Twig\Environment;
use Twig\Runtime\EscaperRuntime;

#[AsTwigComponent(name: 'action')]
class ActionComponent
{
    public ?string $url = null; // for direct URL
    public ?string $route = null; // for url generation
    public ?string $method = null; // POST when null

    public bool|string $turboFrame = false;

    // Button
    public bool|string $label = 'Action';
    public ?string $btnType = 'primary';
    public ?string $btnSize = null;
    public ?string $btnClass = null;
    public bool|string $icon = 'bi:lightning-charge-fill';

    public ?string $name = null; // for CSRF token
    public object $entity; // for CSRF token and url generation

    // Confirmation
    public bool $confirm = false;
    public ?string $confirmTitle = null;
    public ?string $confirmMessage = null;

    public function __construct(
        protected Environment $twig,
    ) {
    }

    public function getBtnAttributes(): ComponentAttributes
    {
        $class = 'btn';

        if ($this->btnType) {
            $class .= " btn-{$this->btnType}";
        }

        if ($this->btnSize) {
            $class .= " btn-{$this->btnSize}";
        }

        if ($this->btnClass) {
            $class .= " {$this->btnClass}";
        }

        return new ComponentAttributes(
            ['class' => $class],
            $this->twig->getRuntime(EscaperRuntime::class)
        );
    }
}
