<?php

namespace App\Service\Framework;

use App\DependencyInjection\Framework\LoggerDI;
use App\DependencyInjection\Framework\SerializerDI;
use App\DependencyInjection\Service\ValidatorServiceDI;
use App\Exception\SoftException;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Request\ParamConverter\ParamConverterInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class RequestQueryParamConverter implements ParamConverterInterface
{
    protected const NAME = 'app.param_converter.request_query';

    use LoggerDI;
    use SerializerDI;
    use ValidatorServiceDI;

    /**
     * @throws \Exception
     */
    public function apply(Request $request, ParamConverter $configuration): bool
    {
        try {
            $queryJson = $this->parseQueryToJson($request);
            $object = $this->serializer->deserialize(
                $queryJson,
                $configuration->getClass(),
                'json'
            );
        }
        catch (\Exception $e) {
            return $this->throwException(new BadRequestHttpException($e->getMessage(), $e), $configuration);
        }

        $request->attributes->set($configuration->getName(), $object);

        if (!$configuration->isOptional() && $error = $this->validatorService->getValidationError($object)) {
            return throw new SoftException($error->getMessage());
        }

        return true;
    }

    public function supports(ParamConverter $configuration): bool
    {
        return null !== $configuration->getClass() && self::NAME === $configuration->getConverter();
    }

    private function parseQueryToJson(Request $request): string
    {
        return $this->serializer->serialize($request->query->all(), 'json');
    }

    /**
     * @throws \Exception
     */
    private function throwException(\Exception $exception, ParamConverter $configuration): bool
    {
        if ($configuration->isOptional()) {
            return false;
        }

        throw $exception;
    }
}
