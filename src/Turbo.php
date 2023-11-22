<?php

namespace Kibatic\UX;

use Symfony\Component\HttpFoundation\RequestStack;

class Turbo
{
    public function __construct(private readonly RequestStack $requestStack)
    {
    }

    public function requestHasFrame(): bool
    {
        return $this->getRequestedFrame() !== null;
    }

    public function getRequestedFrame(): ?string
    {
        return $this->requestStack->getCurrentRequest()->headers->get('turbo-frame');
    }
}
