<?php

namespace App\Annotations\Security;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\ConfigurationAnnotation;

#[\Attribute(\Attribute::TARGET_METHOD)]
class OnlyOwner extends ConfigurationAnnotation
{
    private string $message;

    public function __construct(array  $data = [],
                                string $message = 'Access denied on resource!')
    {
        $values = [];
        if (\is_string($data)) {
            $values['message'] = $data;
        } else {
            $values = $data;
        }

        $props = [];
        $props['message'] = $values['message'] ?? $values['value'] ?? $message;

        parent::__construct($props);
    }

    public function getMessage(): string
    {
        return $this->message;
    }

    public function setMessage(string $message): void
    {
        $this->message = $message;
    }

    public function getAliasName()
    {
        return 'app.security';
    }

    public function allowArray()
    {
        return false;
    }
}
