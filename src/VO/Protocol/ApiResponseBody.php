<?php

namespace App\VO\Protocol;

use App\VO\Protocol\Api\ErrorResponseData;
use Symfony\Component\Serializer\Annotation\Groups;

class ApiResponseBody
{
    #[Groups(['api', 'search'])]
    public bool $success;

    #[Groups(['api', 'search'])]
    public mixed $data;

    #[Groups(['api', 'search'])]
    public ?ErrorResponseData $error = null;

    public function __construct(bool $success, mixed $data, ?string $errorMessage = null, ?int $errorCode = null)
    {
        $this->success = $success;
        $this->data = $data;

        if ($errorMessage) {
            $this->error = new ErrorResponseData($errorMessage, $errorCode);
        }
    }

}
