<?php

namespace App\Service\Framework;

use App\DependencyInjection\Framework\EntityManagerDI;
use App\DependencyInjection\Framework\LoggerDI;
use App\DependencyInjection\Repository\Log\RequestLogRepositoryDI;
use App\DependencyInjection\Service\SecurityServiceDI;
use Doctrine\DBAL\Driver\Exception;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Entity\Log\RequestLog;

class RequestService
{
    const APP_CLIENT_IOS = 'ios-YaM6n76AYee2';
    const APP_CLIENT_ANDROID = 'android-DrDZqPj8Pq2v';
    const APP_CLIENT_ADMIN = 'admin-hNCWtBkG3P75';

    private ?string $appClientId      = null;
    private ?string $appClientVersion = null;
    private ?float  $requestTime      = null;

    use LoggerDI;
    use RequestLogRepositoryDI;
    use SecurityServiceDI;
    use EntityManagerDI;

    public function init(Request $request)
    {
        $this->requestTime = $this->getMicrotime();
        $this->appClientId = $request->headers->get('X-App-Client-Id');
        $this->appClientVersion = $request->headers->get('X-App-Version');
    }

    public function isAdminClientRequest(): bool
    {
        return $this->appClientId === RequestService::APP_CLIENT_ADMIN;
    }

    public function isUserClientRequest(): bool
    {
        return in_array($this->appClientId, [RequestService::APP_CLIENT_IOS, RequestService::APP_CLIENT_ANDROID]);
    }

    public function guessSerializationMainGroupFromClientId(): ?string
    {
        if ($this->isAdminClientRequest()) {
            return 'admin';
        }

        if ($this->isUserClientRequest()) {
            return 'user';
        }

        return null;
    }

    /**
     * Prepend the main serialization group based on incoming request. E.g. "list" results in "admin:list" or "client:list"
     * The resulting groups will be a list containing merged matching groups and unmatched groups
     */
    public function formatSerializationGroupsWithMainGroup(array $serializationGroups, ?string $mainGroup): array
    {
        if (!$mainGroup && $this->appClientId) {
            $mainGroup = $this->guessSerializationMainGroupFromClientId();
        }

        $groups = [];
        foreach ($serializationGroups as $group) {
            if (in_array($group, ['list', 'view'])) {
                $groups[] = "$mainGroup:$group";
            } else {
                $groups[] = $group;
            }
        }

        return $groups;
    }


    public function createRequestLog(Request $request, Response $response, ?\Throwable $exception = null)
    {

        try {
            $log = new RequestLog();
            $log->setUser($this->securityService->getLoggedInUser());
            $log->setMethod($request->getMethod());
            $log->setUri($request->getUri());
            $log->setHeaders(json_encode($request->headers->all()));
            $log->setQueryParams(json_encode($request->query->all()));
            $log->setBodyParams(json_encode($request->request->all()));
            $log->setStatusCode($response->getStatusCode());
            $log->setRequestDuration($this->getMicrotime() - $this->requestTime);

            if (in_array('application/json', $response->headers->all())) {
                $log->setResponseBody($response->getContent());
            }

            if ($exception) {
                $log->setException($exception->getMessage()
                    . PHP_EOL
                    . $exception->getTraceAsString());
            }
            $this->entityManager->persist($log);
            $this->entityManager->flush();
        } catch (\Exception|Exception $e) {
            $this->logger->critical('[createRequestLog] ' . $e->getMessage());
            $this->logger->critical('[createRequestLog] ' . $e->getTraceAsString());
        }
    }

    public function isLoggableStatusCode(int $statusCode): bool
    {
        return in_array($statusCode, [400, 401, 403, 500]);
    }

    public function getAppClientId(): ?string
    {
        return $this->appClientId;
    }

    public function getAppClientVersion(): ?string
    {
        return $this->appClientVersion;
    }

    private function getMicrotime(): float
    {
        return microtime(true);
    }
}
