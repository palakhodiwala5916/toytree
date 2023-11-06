<?php

namespace App\Entity\Log;

use App\Entity\AbstractEntity;
use App\Entity\User\User;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Ignore;

#[ORM\Table(name: 'request_logs')]
#[ORM\Index(name: 'requestLogsCreatedAtUserIdIdx', columns: ['created_at', 'user_id'])]
#[ORM\Index(name: 'requestLogsCreatedAtUriIdx', columns: ['created_at', 'uri'])]
#[ORM\Index(name: 'requestLogsCreatedAtStatusCodeSdx', columns: ['created_at', 'status_code'])]
#[ORM\Index(name: 'requestLogsCreatedAtMethodUriStatusCodeIdx', columns: ['created_at', 'method', 'uri', 'status_code'])]
#[ORM\Entity(repositoryClass: 'App\Repository\Log\RequestLogRepository')]
class RequestLog extends AbstractEntity
{

    #[ORM\Column(name: 'method', type: 'string', length: 10, nullable: false)]
    private string $method;

    #[ORM\Column(name: 'uri', type: 'string', nullable: false)]
    private string $uri;

    #[ORM\Column(name: 'status_code', type: 'integer', nullable: false)]
    private int $statusCode;

    #[ORM\ManyToOne(targetEntity: User::class, cascade: ['persist'])]
    #[ORM\JoinColumn(name: 'user_id', referencedColumnName: 'id', nullable: true, onDelete: 'SET NULL')]
    #[Ignore]
    private ?User $user = null;

    #[ORM\Column(name: 'headers', type: 'text', nullable: true)]
    private ?string $headers = null;

    #[ORM\Column(name: 'query_params', type: 'text', nullable: true)]
    private ?string $queryParams = null;

    #[ORM\Column(name: 'body_params', type: 'text', nullable: true)]
    private ?string $bodyParams = null;

    #[ORM\Column(name: 'response_body', type: 'text', nullable: true)]
    private ?string $responseBody = null;

    #[ORM\Column(name: 'exception', type: 'text', nullable: true)]
    private ?string $exception = null;

    #[ORM\Column(name: 'request_duration', type: 'float', precision: 10, scale: 0, nullable: true)]
    private ?float $requestDuration = null;

    //<editor-fold desc="Getters & Setters" >

    /**
     * @return string
     */
    public function getMethod(): string
    {
        return $this->method;
    }

    /**
     * @param string $method
     */
    public function setMethod(string $method): void
    {
        $this->method = $method;
    }

    /**
     * @return string
     */
    public function getUri(): string
    {
        return $this->uri;
    }

    /**
     * @param string $uri
     */
    public function setUri(string $uri): void
    {
        $this->uri = $uri;
    }

    /**
     * @return int
     */
    public function getStatusCode(): int
    {
        return $this->statusCode;
    }

    /**
     * @param int $statusCode
     */
    public function setStatusCode(int $statusCode): void
    {
        $this->statusCode = $statusCode;
    }

    /**
     * @return User|null
     */
    public function getUser(): ?User
    {
        return $this->user;
    }

    /**
     * @param User|null $user
     */
    public function setUser(?User $user): void
    {
        $this->user = $user;
    }

    /**
     * @return string|null
     */
    public function getHeaders(): ?string
    {
        return $this->headers;
    }

    /**
     * @param string|null $headers
     */
    public function setHeaders(?string $headers): void
    {
        $this->headers = $headers;
    }

    /**
     * @return string|null
     */
    public function getQueryParams(): ?string
    {
        return $this->queryParams;
    }

    /**
     * @param string|null $queryParams
     */
    public function setQueryParams(?string $queryParams): void
    {
        $this->queryParams = $queryParams;
    }

    /**
     * @return string|null
     */
    public function getBodyParams(): ?string
    {
        return $this->bodyParams;
    }

    /**
     * @param string|null $bodyParams
     */
    public function setBodyParams(?string $bodyParams): void
    {
        $this->bodyParams = $bodyParams;
    }

    /**
     * @return string|null
     */
    public function getResponseBody(): ?string
    {
        return $this->responseBody;
    }

    /**
     * @param string|null $responseBody
     */
    public function setResponseBody(?string $responseBody): void
    {
        $this->responseBody = $responseBody;
    }

    /**
     * @return float|null
     */
    public function getRequestDuration(): ?float
    {
        return $this->requestDuration;
    }

    /**
     * @param float|null $requestDuration
     */
    public function setRequestDuration(?float $requestDuration): void
    {
        $this->requestDuration = $requestDuration;
    }

    /**
     * @return string|null
     */
    public function getException(): ?string
    {
        return $this->exception;
    }

    /**
     * @param string|null $exception
     */
    public function setException(?string $exception): void
    {
        $this->exception = $exception;
    }

    //</editor-fold>
}
