<?php

namespace App\EventListener;

use App\DependencyInjection\Framework\EnvironmentDI;
use App\DependencyInjection\Framework\LoggerDI;
use App\DependencyInjection\Framework\RequestServiceDI;
use App\DependencyInjection\Framework\TranslatorDI;
use App\DependencyInjection\Framework\UrlGeneratorDI;
use App\Exception\ApiException;
use App\Exception\SoftException;
use App\VO\Protocol\ApiResponseBody;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use Symfony\Component\HttpFoundation\HeaderBag;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpKernel\Event\ResponseEvent;

class RequestListener
{
    use LoggerDI;
    use EnvironmentDI;
    use TranslatorDI;
    use RequestServiceDI;
    use UrlGeneratorDI;

    private ?\Throwable $exception = null;

    public function onKernelRequest(RequestEvent $event)
    {
        if (!$event->isMainRequest()) {
            // don't do anything if it's not the main request
            return;
        }

        $this->requestService->init($event->getRequest());

        $route = $event->getRequest()->get('_route');
        $headers = new HeaderBag($event->getRequest()->headers->all());

        $this->logger->info(sprintf('[%s] %s headers: %s, query: %s, body: %s',
            $route,
            $event->getRequest()->getMethod(),
            json_encode($headers->all()),
            json_encode($event->getRequest()->query->all()),
            json_encode($event->getRequest()->request->all())
        ));
    }

    public function onKernelResponse(ResponseEvent $event)
    {
        if (!$event->isMainRequest()) {
            // don't do anything if it's not the main request
            return;
        }

        $message = sprintf('[%s] %d %s',
            $event->getRequest()->get('_route'),
            $event->getResponse()->getStatusCode(),
            $event->getResponse()->getContent()
        );

        $statusCode = $event->getResponse()->getStatusCode();
        if ($statusCode === Response::HTTP_INTERNAL_SERVER_ERROR) {
            $this->logger->critical($message);
        } elseif ($statusCode >= Response::HTTP_BAD_REQUEST) {
            $this->logger->error($message);
        } else {
            $this->logger->info($message);
        }

       // if ($this->requestService->isLoggableStatusCode($statusCode)) {
            $this->requestService->createRequestLog($event->getRequest(), $event->getResponse(), $this->exception);
       // }
    }

    public function onKernelException(ExceptionEvent $event)
    {
        $path = $event->getRequest()->getPathInfo();
        $route = $event->getRequest()->get('_route');
        $exception = $event->getThrowable();
        $this->exception = $event->getThrowable();

        if (!str_starts_with($path, '/api/v1')) {
            if (!empty($path) && empty($route)) {
                if (str_starts_with($path, '/')) {
                    $event->setResponse(new RedirectResponse(
                        $this->urlGenerator->generate('sonata_user_admin_security_login')
                    ));
                }
            }
        }

        if ($exception instanceof SoftException) {
            $this->logger->warning(sprintf('[%s] %s', $route, $exception->getMessage()));
            $this->logger->warning(sprintf('[%s] %s', $route, $exception->getTraceAsString()));
        } elseif ($exception instanceof ApiException) {
            $this->logger->error(sprintf('[%s] %s', $route, $exception->getMessage()));
            $this->logger->error(sprintf('[%s] %s', $route, $exception->getTraceAsString()));
        } elseif ($exception instanceof UniqueConstraintViolationException) {
            $this->logger->error(sprintf('[%s] %s', $route, $exception->getMessage()));
            $this->logger->error(sprintf('[%s] %s', $route, $exception->getTraceAsString()));
        } else {
            $this->logger->critical(sprintf('[%s] %s', $route, $exception->getMessage()));
            $this->logger->critical(sprintf('[%s] %s', $route, $exception->getTraceAsString()));
        }

        if (!str_starts_with($route, 'api_')) {
            return;
        }

        $message = $this->translator->trans($exception->getMessage());

        if ($exception->getCode() >= Response::HTTP_BAD_REQUEST && $exception->getCode() <= Response::HTTP_INTERNAL_SERVER_ERROR) {
            $statusCode = $exception->getCode();
        } else {
            $statusCode = Response::HTTP_INTERNAL_SERVER_ERROR;
        }

        $data = new ApiResponseBody(false, [], $message);
        $response = new JsonResponse($data, $statusCode);
        $event->setResponse($response);
//        $event->getResponse()->setContent($response->getContent());
//        $event->setResponse($response);
    }

}
