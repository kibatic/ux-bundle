<?php

namespace Kibatic\UX\Twig;

use Doctrine\Common\Collections\Collection;
use Symfony\Component\Intl\Languages;
use Symfony\UX\StimulusBundle\Dto\StimulusAttributes;
use Symfony\UX\StimulusBundle\Helper\StimulusHelper;
use Twig\Environment;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;
use Twig\TwigFunction;

class AppExtension extends AbstractExtension
{
    private StimulusHelper $stimulusHelper;

    public function __construct(
        Environment $twigEnvironment,
    )
    {
        $this->stimulusHelper = new StimulusHelper($twigEnvironment);
    }

    public function getFilters(): array
    {
        return [
            new TwigFilter('live_action', $this->appendLiveAction(...), ['is_safe' => ['html_attr']]),
            new TwigFilter('language_code_3to2', $this->language_code_3to2(...)),
        ];
    }

    public function getFunctions(): array
    {
        return [
            new TwigFunction('live_action', $this->renderLiveAction(...), ['is_safe' => ['html_attr']]),
            new TwigFunction('create_stimulus_attributes', $this->createStimulusAttributes(...))
        ];
    }

    public function renderLiveAction(string $actionName, array $parameters): StimulusAttributes
    {
        $stimulusAttributes = $this->stimulusHelper->createStimulusAttributes();
        $stimulusAttributes->addAction('live', 'action', null, [
            'action' => $actionName,
            ...$parameters
        ]);

        return $stimulusAttributes;
    }

    public function appendLiveAction(StimulusAttributes $stimulusAttributes, string $actionName, array $parameters): StimulusAttributes
    {
        $stimulusAttributes->addAction('live', 'action', null, [
            'action' => $actionName,
            ...$parameters
        ]);

        return $stimulusAttributes;
    }

    public function createStimulusAttributes(): StimulusAttributes
    {
        return $this->stimulusHelper->createStimulusAttributes();
    }
}
