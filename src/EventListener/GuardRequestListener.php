<?php

namespace App\EventListener;

use App\Annotations\Security\HasRouteAccess;
use App\Annotations\Security\OnlyOwner;
use App\DependencyInjection\Framework\LoggerDI;
use App\DependencyInjection\Framework\TranslatorDI;
//use App\DependencyInjection\Service\SecurityServiceDI;
use Doctrine\Common\Annotations\Reader;
use Doctrine\ORM\EntityNotFoundException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Event\ControllerEvent;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

class GuardRequestListener
{
    use LoggerDI;
//    use SecurityServiceDI;
    use TranslatorDI;

    private $annotationReader;

    public function __construct(Reader $annotationReader)
    {
        $this->annotationReader = $annotationReader;
    }

    /**
     * @throws \ReflectionException
     * @throws EntityNotFoundException
     */
    public function onKernelController(ControllerEvent $event): void
    {
        if (!$event->isMainRequest()) {
            return;
        }

        $this->checkIfRouteIsRestrictedToOwner($event->getRequest());

        $controllers = $event->getController();
        if (!is_array($controllers)) {
            return;
        }

        if ($this->hasGuardAnnotation($controllers)) {
            $this->handleRouteAccess($event->getRequest());
        }
    }

    private function handleRouteAccess(Request $request): void
    {
//        if (!$user = $this->securityService->getLoggedInUser()) {
//            throw new AccessDeniedException();
//        }

//        $roles = $user->getAllRoles();

//        if (!in_array($request->attributes->get('_route'), $roles)) {
//            throw new AccessDeniedException();
//        }
    }

    private function hasGuardAnnotation(iterable $controllers): bool
    {
        list($controller, $method) = $controllers;

        try {
            $controller = new \ReflectionClass($controller);
            $method = $controller->getMethod($method);
        } catch (\ReflectionException $e) {
            $this->logger->critical($e->getMessage());

            throw new \RuntimeException('Failed to read annotation!');
        }

        $annotation = $this->annotationReader->getMethodAnnotation($method, HasRouteAccess::class);

        return $annotation instanceof HasRouteAccess;
    }

    /**
     * @throws \ReflectionException
     * @throws EntityNotFoundException
     */
    private function checkIfRouteIsRestrictedToOwner(Request $request)
    {
        // only relevant to client requests
        if (!str_starts_with($request->get('_route'), 'api_client')) {
            return false;
        }

        // skip if there are no route parameters defined
        if (empty($request->attributes->get('_route_params'))) {
            return false;
        }

        $method = new \ReflectionMethod($request->attributes->get('_controller'));
        $annotation = $this->annotationReader->getMethodAnnotation($method, OnlyOwner::class);

        if (!$annotation) {
            return false;
        }

//        $loggedInUser = $this->securityService->getLoggedInUser();
        foreach ($method->getParameters() as $param) {
            if (!$param->getType() instanceof \ReflectionNamedType) {
                continue;
            }
            if ($param->getType()->getName() === Request::class) {
                continue;
            }

            $param = $request->get($param->getName());

//            if (method_exists($param, 'getUser') && $param->getUser()->getId() !== $loggedInUser->getId()) {
//                throw new AccessDeniedException($this->translator->trans($annotation->getMessage()));
//            }
        }

        return true;
    }
}
