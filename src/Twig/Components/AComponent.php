<?php

namespace Kibatic\UX\Twig\Components;

use Symfony\Component\Translation\TranslatableMessage;
use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;

// TODO: voir si le CVA peut améliorer la gestion des variantes (https://symfony.com/bundles/ux-twig-component/current/index.html#component-with-complex-variants-cva)
class AComponent
{
    public ?string $type = 'primary';
    public ?string $icon = null;
    public string $iconPosition = 'left';
    public bool $iconOnly = false;

    public bool $modal = false;
    public string $modalFrameId = 'page-content';
    public string $modalFrameTarget = '_top';
    public bool $modalCloseOnSuccess = false;
    public bool $modalStayOnSuccess = false;
    public array $modalRelatedTurboFrames = [];

    public array $liveAction = [];
    public bool $confirm = false;

    protected array $superTypes = [];

    public function __construct()
    {
        $this->superTypes = [
            'new' => [
                'icon' => 'bi-plus-circle',
                'content' => new TranslatableMessage('Add'),
            ],
            'edit' => [
                'icon' => 'bi-pencil',
                'content' => new TranslatableMessage('Edit'),
            ],
            'show' => [
                'icon' => 'bi-eye',
                'content' => new TranslatableMessage('Show'),
            ],
            'save' => [
                'icon' => 'bi-save2',
                'content' => new TranslatableMessage('Save'),
            ],
            'delete' => [
                'icon' => 'bi-trash',
                'content' => new TranslatableMessage('Delete'),
                'type' => 'outline-danger',
            ],
            'back' => [
                'icon' => 'bi-arrow-left-circle',
                'content' => new TranslatableMessage('Back'),
                'type' => 'secondary',
            ],
            'prev' => [
                'icon' => 'bi-arrow-left-circle',
                'content' => new TranslatableMessage('Previous'),
                'type' => 'secondary',
            ],
            'next' => [
                'icon' => 'bi-arrow-right-circle',
                'content' => new TranslatableMessage('Next'),
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

        if (!strpos($icon, ' ')) {
            $icon = "bi $icon";
        }

        return $icon;
    }

    public function getContent(): ?string
    {
        return $this->getSuperType($this->type)['content'] ?? null;
    }

    public function getIconPosition(): ?string
    {
        return $this->getSuperType($this->type)['icon_position'] ?? $this->iconPosition;
    }
}
