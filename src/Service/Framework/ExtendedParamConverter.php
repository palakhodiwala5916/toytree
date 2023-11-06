<?php

namespace App\Service\Framework;

use App\DependencyInjection\Framework\LoggerDI;
use App\DependencyInjection\Service\ValidatorServiceDI;
use App\DependencyInjection\Framework\TranslatorDI;
use App\Exception\ApiException;
use App\Exception\SoftException;
use App\VO\Protocol\ApiResponseBody;
use App\VO\Protocol\RequestBodyInterface;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use FOS\RestBundle\Request\RequestBodyParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Request\ParamConverter\ParamConverterInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\Validator\ConstraintViolationListInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;

class ExtendedParamConverter implements ParamConverterInterface
{
    use LoggerDI;
    use ValidatorServiceDI;
    use TranslatorDI;

    private RequestBodyParamConverter $parentConverter;

    public function __construct(RequestBodyParamConverter $parentConverter) { $this->parentConverter = $parentConverter; }

    /**
     * @throws SoftException
     */
    public function apply(Request $request, ParamConverter $configuration): bool
    {
        if ($this->isRequestBodyInterface($configuration)) {
            $className = $this->getPostBodyClassNameFromRequestBodyInterface($request, $configuration);
            $configuration->setClass($className);
        }

        $isApplied = $this->parentConverter->apply($request, $configuration);

        if ($isApplied === true) {
            $errors = $request->attributes->get('validationErrors');

            if ($errors instanceof ConstraintViolationListInterface && $errors->count() > 0) {
                return throw new SoftException($errors->get(0)->getMessage());
            }

            $object = $request->attributes->get($configuration->getName());
            if (!$configuration->isOptional() && $error = $this->validatorService->getValidationError($object)) {
                return throw new SoftException($error->getMessage());
            }
        }

        return $isApplied;
    }

    public function supports(ParamConverter $configuration): bool
    {
        return $this->parentConverter->supports($configuration);
    }

    private function isRequestBodyInterface(ParamConverter $configuration): bool
    {
        return $configuration->getClass() === RequestBodyInterface::class;
    }

    private function getPostBodyClassNameFromRequestBodyInterface(Request $request, ParamConverter $configuration): ?string
    {
        $controllerClassName = explode('::', $request->attributes->get('_controller'))[0];

        if (method_exists($controllerClassName, 'postBodyClassName')) {
            return call_user_func("$controllerClassName::postBodyClassName");
        }

        return $configuration->getClass();
    }

    public function onKernelException(ExceptionEvent $event)
    {
        $route = $event->getRequest()->get('_route');
        $exception = $event->getThrowable();
        $this->exception = $event->getThrowable();

        if ($exception instanceof SoftException) {
            $this->logger->warning(sprintf('[%s] %s', $route, $exception->getMessage()));
            $this->logger->warning(sprintf('[%s] %s', $route, $exception->getTraceAsString()));
        } elseif ($exception instanceof ApiException) {
            $this->logger->error(sprintf('[%s] %s', $route, $exception->getMessage()));
            $this->logger->error(sprintf('[%s] %s', $route, $exception->getTraceAsString()));
        }elseif ($exception instanceof UniqueConstraintViolationException) {
            $this->logger->error(sprintf('[%s] %s', $route, $exception->getMessage()));
            $this->logger->error(sprintf('[%s] %s', $route, $exception->getTraceAsString()));
        } else {
            $this->logger->critical(sprintf('[%s] %s', $route, $exception->getMessage()));
            $this->logger->critical(sprintf('[%s] %s', $route, $exception->getTraceAsString()));
        }

        if (!str_starts_with($route, 'api_')) {
            return;
        }

        if ($exception->getCode() >= Response::HTTP_BAD_REQUEST && $exception->getCode() <= Response::HTTP_INTERNAL_SERVER_ERROR) {
            $statusCode = $exception->getCode();
        } else {
            $statusCode = Response::HTTP_INTERNAL_SERVER_ERROR;
        }

        $data = new ApiResponseBody(false, null, $this->translator->trans($exception->getMessage()));
        $response = new JsonResponse($data, $statusCode);
        $event->setResponse($response);
    }
}
