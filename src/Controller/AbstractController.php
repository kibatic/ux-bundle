<?php

namespace Kibatic\UX\Controller;

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
    public function createForm(string $type, mixed $data = null, array $options = [], bool $autoAction = true): FormInterface
    {
        if ($autoAction) {
            $options['action'] = $options['action'] ?? $this->requestStack->getCurrentRequest()->getUri();
        }

        return parent::createForm($type, $data, $options);
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
}
