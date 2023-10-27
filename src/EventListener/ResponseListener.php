<?php

namespace Kibatic\UX\EventListener;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\ResponseEvent;
use Symfony\UX\Turbo\TurboBundle;
use Twig\Environment;

final class ResponseListener
{
    public function __construct(private readonly Environment $twig)
    {
    }

    public function onKernelResponse(ResponseEvent $event): void
    {
        // Si la requête vient de Turbo et qu'on souhaite rester sur la même page,
        // on définit le content type à "text/vnd.turbo-stream.html"
        // et on utilise le template des notifications au format turbo stream.
        if ($event->getRequest()->headers->get('turbo-on-success') === 'stay'
            && $event->getRequest()->getPreferredFormat() === TurboBundle::STREAM_FORMAT
        ) {
            if (!$event->getResponse()->isSuccessful()) {
                return;
            }

            $event->getRequest()->setRequestFormat(TurboBundle::STREAM_FORMAT);
            $event->getResponse()
                ->setStatusCode($event->getResponse()->getStatusCode())
                ->setContent($this->twig->render('common/notifications.stream.html.twig'))
            ;
        }
    }
}
