<?php

namespace Kibatic\UX\Controller;

use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\Translation\TranslatableMessage;
use Symfony\Contracts\Translation\TranslatorInterface;
use Symfony\UX\Turbo\TurboBundle;
use Twig\Environment;

class AbstractController extends \Symfony\Bundle\FrameworkBundle\Controller\AbstractController
{
    public function __construct(
        protected RequestStack $requestStack,
        protected Environment $twig,
        protected TranslatorInterface $translator,
    ) {
    }

    protected function render(string $view, array $parameters = [], ?Response $response = null, bool $handleTurboOnSuccess = true): Response
    {
        $request = $this->requestStack->getMainRequest();

        if ($handleTurboOnSuccess
            && $request->headers->get('turbo-on-success') === 'stay'
            && $request->getPreferredFormat() === TurboBundle::STREAM_FORMAT
        ) {
            $request->setRequestFormat(TurboBundle::STREAM_FORMAT);
            return (new Response())
                ->setContent($this->twig->render('@KibaticUX/notifications.stream.html.twig'));
        }

        return parent::render($view, $parameters, $response);
    }

    public function redirectToReferrer(?string $fallbackRoute = null): RedirectResponse
    {
        $referer = $this->requestStack->getCurrentRequest()->headers->get('referer');

        if (!$referer) {
            if ($fallbackRoute) {
                return $this->redirectToRoute($fallbackRoute);
            } else {
                return new RedirectResponse('/');
            }
        }

        return $this->redirect($referer);
    }
    
    public function createForm(string $type, mixed $data = null, array $options = [], bool $autoAction = true): FormInterface
    {
        if ($autoAction) {
            $options['action'] = $options['action'] ?? $this->requestStack->getCurrentRequest()->getUri();
        }

        return parent::createForm($type, $data, $options);
    }

    public function createFormBuilder(mixed $data = null, array $options = [], bool $autoAction = true): FormBuilderInterface
    {
        if ($autoAction) {
            $options['action'] = $options['action'] ?? $this->requestStack->getCurrentRequest()->getUri();
        }

        return parent::createFormBuilder($data, $options);
    }

    protected function redirectToRoute(string $route, array $parameters = [], int $status = Response::HTTP_SEE_OTHER): RedirectResponse
    {
        return parent::redirectToRoute($route, $parameters, $status);
    }

    protected function assertActionCsrfTokenValid(Request $request, ?object $entity = null, ?string $actionName = null): void
    {
        $tokenId = ($actionName ?? $request->attributes->get('_route')) . ($entity ? '_' . $entity->getId() : '');
        $token = $request->request->get('_token') ?? $request->query->get('_token');

        if (!$this->isCsrfTokenValid($tokenId, $token)) {
            throw new AccessDeniedHttpException("Invalid CSRF token for id : $tokenId.");
        }
    }

    protected function isCurrentRequestTheMainRequest(): bool
    {
        return $this->requestStack->getParentRequest() === null;
    }

    protected function getMainRequest(): Request
    {
        return $this->requestStack->getMainRequest();
    }

    public function addAlert(string $icon, null|string|TranslatableMessage $title = null, null|string|TranslatableMessage $text = null, array $options = []): void
    {
        $options = $options + [
            'icon' => $icon,
            'title' => $title,
            'text' => $text,
            'showConfirmButton' => true,
        ];

        $this->addCustomAlert($options);
    }

    public function addQuickAlert(string $icon, null|string|TranslatableMessage $title = null, null|string|TranslatableMessage $text = null, array $options = [], ?string $namespace = null): void
    {
        $options = $options + [
            'icon' => $icon,
            'title' => $title,
            'text' => $text,
            'timer' => 3000,
            'timerProgressBar' => true,
            'showConfirmButton' => false,
        ];

        $this->addCustomAlert($options, $namespace);
    }

    /**
     * Attention, malgré le nom de "toast", ça reste une alerte et il ne peut y en avoir qu'une à la fois.
     */
    public function addToastAlert(string $icon, null|string|TranslatableMessage $title = null, null|string|TranslatableMessage $text = null, array $options = []): void
    {
        $options = [
            ...$options,
            'toast' => true,
            'position' => 'top-end',
        ];

        $this->addQuickAlert($icon, $title, $text, $options);
    }

    public function addCustomAlert($options = [], ?string $namespace = null): void
    {
        foreach ($options as $k => $v) {
            if ($v instanceof TranslatableMessage) {
                $options[$k] = $this->translator->trans($v);
            }
        }

        $this->addFlash($namespace ?? 'sweet-alert', ['options' => $options]);
    }
}
