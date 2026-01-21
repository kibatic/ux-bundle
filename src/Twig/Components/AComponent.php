<?php

namespace Kibatic\UX\Twig\Components;

use Symfony\Component\Translation\TranslatableMessage;
use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;
use Symfony\UX\TwigComponent\ComponentAttributes;
use Twig\Environment;
use Twig\Runtime\EscaperRuntime;
use function Symfony\Component\Translation\t;

// TODO: voir si le CVA peut améliorer la gestion des variantes (https://symfony.com/bundles/ux-twig-component/current/index.html#component-with-complex-variants-cva)
class AComponent
{
    public ?string $type = 'primary';
    public ?string $icon = null;
    public string $iconPosition = 'left';
    public bool $iconOnly = false;
    public array $attr = [];

    public bool $modal = false;
    public string $modalFrameId = 'page-content';
    public string $modalFrameTarget = '_top';
    public bool $modalCloseOnSuccess = false;
    public bool $modalStayOnSuccess = false;
    public array $modalRelatedTurboFrames = [];
    public ?string $modalStackId = null;

    public array $liveAction = [];
    public bool $confirm = false;
    public ?string $confirmTitle = null;
    public ?string $confirmText = null;


    protected array $superTypes = [];

    public function __construct(
        protected Environment $twig,
    ) {
        $this->superTypes = [
            'new' => [
                'icon' => 'bi:plus-circle',
                'content' => t('Add'),
            ],
            'edit' => [
                'icon' => 'bi:pencil',
                'content' => t('Edit'),
            ],
            'show' => [
                'icon' => 'bi:eye',
                'content' => t('Show'),
            ],
            'save' => [
                'icon' => 'bi:save2',
                'content' => t('Save'),
            ],
            'delete' => [
                'icon' => 'bi:trash',
                'content' => t('Delete'),
                'type' => 'outline-danger',
            ],
            'back' => [
                'icon' => 'bi:arrow-left-circle',
                'content' => t('Back'),
                'type' => 'secondary',
            ],
            'prev' => [
                'icon' => 'bi:arrow-left-circle',
                'content' => t('Previous'),
                'type' => 'secondary',
            ],
            'next' => [
                'icon' => 'bi:arrow-right-circle',
                'content' => t('Next'),
                'type' => 'primary',
                'icon_position' => 'right'
            ],
        ];
    }

    public function getSuperType(?string $type): ?array
    {
        return $this->superTypes[$type] ?? null;
    }

    public function getType(): string
    {
        return $this->getSuperType($this->type)['type'] ?? $this->type;
    }

    public function getIcon(): ?string
    {
        $icon = $this->getSuperType($this->type)['icon'] ?? $this->icon;

         if (!$icon) {
            return null;
        }

        return $icon;
    }

    public function getContent(): string|TranslatableMessage|null
    {
        return $this->getSuperType($this->type)['content'] ?? null;
    }

    public function getIconPosition(): ?string
    {
        return $this->getSuperType($this->type)['icon_position'] ?? $this->iconPosition;
    }

    public function getAttr(): ComponentAttributes
    {
        return new ComponentAttributes($this->attr, $this->twig->getRuntime(EscaperRuntime::class));
    }
}
