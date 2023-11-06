<?php

namespace App\Service\Framework;

use App\DependencyInjection\Framework\LoggerDI;
use App\DependencyInjection\Framework\RequestServiceDI;
use App\DependencyInjection\Framework\SerializerDI;
use App\DependencyInjection\Service\ValidatorServiceDI;
use App\Exception\SoftException;
use App\VO\Protocol\ApiResponseBody;
use App\VO\Protocol\ResponseDataInterface;
use Doctrine\Common\Collections\Collection;
use FOS\RestBundle\View\View;
use FOS\RestBundle\View\ViewHandler;
use FOS\RestBundle\View\ViewHandlerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\Normalizer\AbstractObjectNormalizer;

class ApiViewHandler implements ViewHandlerInterface
{
    use LoggerDI;
    use RequestServiceDI;
    use ValidatorServiceDI;
    use SerializerDI;

    private ViewHandler $parentHandler;

    public function __construct(ViewHandler $parentHandler)
    {
        $this->parentHandler = $parentHandler;
    }

    public function supports(string $format): bool
    {
        return $this->parentHandler->supports($format);
    }

    public function registerHandler(string $format, callable $callable)
    {
        $this->parentHandler->registerHandler($format, $callable);
    }

    /**
     * @throws SoftException
     */
    public function handle(View $view, Request $request = null): Response
    {
        $data = $view->getData();
        $isAllowedType = is_float($data) ||
            is_int($data) ||
            is_numeric($data) ||
            is_string($data) ||
            is_bool($data) ||
            is_array($data) ||
            $data instanceof Collection ||
            $data instanceof ResponseDataInterface;

        if ($data instanceof ResponseDataInterface) {
            $this->validatorService->validateResponseData($view->getData());
        }
        if ($isAllowedType) {
            $view->setData(new ApiResponseBody(true, $view->getData()));
        }

        $viewGroups = (array)$view->getContext()->getGroups();

        $mainGroup = $this->requestService->guessSerializationMainGroupFromClientId();
        $serializerGroups = $this->requestService->formatSerializationGroupsWithMainGroup($viewGroups, $mainGroup);

        $json = $this->serializer->serialize($view->getData(), 'json',  ['groups' => $serializerGroups, AbstractObjectNormalizer::SKIP_NULL_VALUES => true, AbstractObjectNormalizer::ENABLE_MAX_DEPTH => true]);

        return new Response($json, 200, array(
            'Content-Type' => 'application/json'
        ));

    }

    public function createRedirectResponse(View $view, string $location, string $format): Response
    {
        $this->logger->info("talent: createRedirectResponse");
        return $this->parentHandler->createRedirectResponse($view, $location, $format);
    }

    public function createResponse(View $view, Request $request, string $format): Response
    {
        $this->logger->info("talent: createResponse");
        return $this->parentHandler->createResponse($view, $request, $format);
    }
}
