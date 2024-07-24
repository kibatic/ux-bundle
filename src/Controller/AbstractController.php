<?php

namespace Kibatic\UX\Controller;

use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

class AbstractController extends \Symfony\Bundle\FrameworkBundle\Controller\AbstractController
{
    public function __construct(protected RequestStack $requestStack)
    {
    }

    public function redirectToReferrer(): RedirectResponse
    {
        return $this->redirect($this->requestStack->getCurrentRequest()->headers->get('referer'));
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

    protected function assertActionCsrfTokenValid(Request $request, object $entity, ?string $actionName = null): void
    {
        $tokenId = ($actionName ?? $request->get('_route')).'_'.$entity->getId();

        if (!$this->isCsrfTokenValid($tokenId, $request->get('_token'))) {
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

    public function addAlert(string $icon, ?string $title = null, ?string $text = null, array $options = []): void
    {
        $options = $options + [
            'icon' => $icon,
            'title' => $title,
            'text' => $text,
            'showConfirmButton' => true,
        ];

        $this->addCustomAlert($options);
    }

    public function addQuickAlert(string $icon, ?string $title = null, ?string $text = null, array $options = [], ?string $namespace = null): void
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
    public function addToastAlert(string $icon, ?string $title = null, ?string $text = null, array $options = []): void
    {
        $options = [
            'toast' => true,
            'position' => 'top-end',
        ] + $options;

        $this->addQuickAlert($icon, $title, $text, $options);
    }

    public function addCustomAlert($options = [], ?string $namespace = null): void
    {
        $this->addFlash($namespace ?? 'sweet-alert', ['options' => $options]);
    }
}
