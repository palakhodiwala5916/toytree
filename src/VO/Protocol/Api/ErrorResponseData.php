<?php

namespace App\VO\Protocol\Api;

use Symfony\Component\Serializer\Annotation\Groups;

class ErrorResponseData
{
    #[Groups(['api'])]
    public string $message;

    #[Groups(['api'])]
    public ?int $code = null;

    public function __construct(?string $message = null, ?int $code = null)
    {
        $this->message = $message;
        $this->code = $code;
    }
}
